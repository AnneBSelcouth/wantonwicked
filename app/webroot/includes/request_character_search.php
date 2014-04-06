<?php

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('request_id', 0);
$onlySanctioned = Request::GetValue('only_sanctioned', true);
$term = Request::GetValue('term');

$requestRepository = new RequestRepository();
$characters = $requestRepository->SearchCharactersForRequest($requestId, $onlySanctioned, $term);

$list = array();
foreach($characters as $i => $character)
{
    $list[$i]['label'] = $character['character_name'];
    $list[$i]['id'] = $character['id'];
}

if(count($list) == 0)
{
    $list[0]['label'] = 'No characters';
    $list[0]['id'] = -1;
}

Response::SendJson($list);