<?php
class XmlFactory{
	private $xmlRoot;
	
	function __construct(){
	}
	
	public function xmlSimpleRead($version='1.0', $encoding='utf-8'){
		$document = simplexml_load_file("./resource/xml/table.xml");
		$table = array();$index = 0;
		foreach ($document->thead->th as $value){
			$table[0][$index++] = (string)$value;
		}
		
		$i = 0;
		foreach ($document->tbody->tr as $item){
			$index = 0;
			foreach ($item as $value){
				$table[1][$i][$index++]= (string)$value;
			}
			$i++;
		}
		
		return $table;
	}
	
	public function xmlWrite($data, $num, $version='1.0', $encoding='utf-8'){
		$document = new DOMDocument($version, $encoding);
		$document->formatOutput = true;
		$root = $document->createElement("table");
		$document->appendChild($root);
		$head = $document->createElement("thead");
		foreach ($data[0] as $key=>$value){
			$node = $document->createElement('th');
			$content = $document->createTextNode($value);
			$node->appendChild($content);
			$head->appendChild($node);
		}
		
		$body = $document->createElement('tbody');
		foreach ($data[1] as $key=>$value){
			$row = $document->createElement('tr');
			foreach ($value as $item){
				$node = $document->createElement('td');
				$content = $document->createTextNode($item);
				$node->appendChild($content);
				$row->appendChild($node);
			}
			$body->appendChild($row);
		}
		$root->appendChild($head);
		$root->appendChild($body);
		$document->save("./resource/xml/{$num}.xml");
	} 
}