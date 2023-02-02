<?php

declare(strict_types = 1);

namespace App\Model;

use Exception;
use SimpleXMLElement;
use Tracy\Debugger;

abstract class BaseXmlRepository
{

	protected ?string $table;

	private string $databasePath;

	private SimpleXMLElement $xml;


	public function __construct()
	{
		$this->setup();
		$this->connect();
	}


	abstract protected function setup(): void;


	private function connect(): void
	{
		// Path to database file
		$this->databasePath = sprintf('%s/../../database/%s.xml', __DIR__, $this->table);

		if (!file_exists($this->databasePath)) {
			$xml = new SimpleXMLElement('<database/>');
			$xml->addChild('row');

			$xml->saveXML($this->databasePath);
		}

		try {
			$xml = file_get_contents($this->databasePath);

			if ($xml !== FALSE) {
				$this->xml = new SimpleXMLElement($xml);
			} else {
				Debugger::log('Some log');
			}

		} catch (Exception $exception) {
			Debugger::log($exception->getMessage());
		}
	}


	/**
	 * @param array<string, int|string> $data
	 * @return void
	 */
	public function insert(array $data): void
	{
		$columns = $this->getColumns();

		// first insert - no columns
		if (count($columns) === 0) {

			// add id if not set
			if (!array_key_exists('id', $data)) {
				$this->setColumn('id', '1');
			}

			foreach ($data as $name => $value) {
				$this->setColumn($name, (string) $value);
			}

			return;
		}

		/** @var SimpleXMLElement $row */
		$row = $this->addRow();

		foreach ($columns as $column) {

			if ($column === 'id') {
				$value = $this->getLastId() + 1;
				$row->addChild('id', (string) $value);
				continue;
			}

			$value = $data[$column] ?? '';
			$row->addChild($column, (string) $value);

		}

		$this->save();
	}


	/**
	 * @return array<int|string, mixed>|null
	 */
	public function getAll(): ?array
	{
		$data = [];

		foreach ($this->xml->children() as $child) {
			$data[] = $this->xml2array($child);
		}

		if (count($data) === 0) {
			return NULL;
		}

		return $data;
	}


	/**
	 * @param int $id
	 * @return array<int|string, mixed>|null
	 */
	public function getById(int $id): ?array
	{

		foreach ($this->xml->children() as $child) {
			foreach ($child as $subfield) {
				if ($subfield->getName() === 'id' && (int) $subfield[0] === $id) {
					return $this->xml2array($child);
				}
			}
		}

		return NULL;
	}


	/**
	 * @param int                  $id
	 * @param array<string, mixed> $data
	 * @return void
	 */
	public function updateById(int $id, array $data): void
	{
		/** @var SimpleXMLElement[] $node */
		$node = $this->xml->xpath('//database/row[id="' . $id . '"]');

		foreach ($node as $row) {
			$dom = dom_import_simplexml($row);
			foreach ($data as $key => $item) {
				if ($dom->getElementsByTagName($key)->item(0) !== NULL) {
					$dom->getElementsByTagName($key)->item(0)->nodeValue = (string) $item;
				}
			}

			$this->save();
		}
	}


	public function deleteById(int $id): void
	{
		foreach ($this->xml->children() as $child) {
			foreach ($child as $subchild) {
				if ($subchild->getName() === 'id' && (int) $subchild[0] === $id) {
					$dom = dom_import_simplexml($child);
					$dom->parentNode?->removeChild($dom);
				}
			}
		}

		$this->save();
	}


	public function removeDatabase(): bool
	{
		return unlink($this->databasePath);
	}


	/**
	 * @return array<int, string>
	 */
	private function getColumns(): array
	{
		$rows = $this->xml->xpath('//database/row[position()=1]');

		if (empty($rows)) {
			return [];
		}

		$columns = [];

		foreach ($rows[0] as $column) {
			$columns[] = $column->getName();
		}

		return $columns;
	}


	private function setColumn(string $name, ?string $value = ''): void
	{
		foreach ($this->xml->xpath('//database/row') as $row) {
			if (isset($row->$name)) {
				continue;
			}

			$row->addChild($name, (string) $value);
		}

		$this->save();
	}


	private function getLastId(): int
	{
		$rows = $this->xml->xpath('//database/row');

		return count($rows) - 1;
	}


	private function addRow(): SimpleXMLElement
	{
		$table = $this->xml->xpath('//database');

		return $table[0]->addChild('row');
	}


	private function save(): void
	{
		$this->xml->saveXML($this->databasePath);
	}


	/**
	 * @param array<string|int, mixed>|object $xmlObject
	 * @return array<string|int, mixed>
	 */
	private function xml2array(array|object $xmlObject): array
	{
		$out = [];

		foreach ((array) $xmlObject as $index => $node) {
			$out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
		}

		return $out;
	}

}