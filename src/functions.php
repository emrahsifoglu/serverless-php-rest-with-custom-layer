<?php

/**
* Parse arbitrary multipart/form-data content
* Note: null result or null values for headers or value means error
* @return array|null [{"headers":array|null,"value":string|null}]
* @param string|null $boundary
* @param string|null $content
*/
function parseMultipartContent(?string $content, ?string $boundary): ?array {
  if(empty($content) || empty($boundary)) return null;
  $sections = array_map("trim", explode("--$boundary", $content));
  $parts = [];
  foreach($sections as $section) {
    if($section === "" || $section === "--") continue;
    $fields = explode("\r\n\r\n", $section);
    if(preg_match_all("/([a-z0-9-_]+)\s*:\s*([^\r\n]+)/iu", $fields[0] ?? "", $matches, PREG_SET_ORDER) === 2) {
      $headers = [];
      foreach($matches as $match) $headers[$match[1]] = $match[2];
    } else $headers = null;
    $parts[] = ["headers" => $headers, "value"   => $fields[1] ?? null];
  }
  return empty($parts) ? null : $parts;
}