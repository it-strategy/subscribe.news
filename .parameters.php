<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!IsModuleInstalled("iblock") || !CModule::IncludeModule("iblock"))
	return;

$arSites=array();
$defSite="";
$sitesSort="SORT";
$sitesBy="ASC";
$rsSite = CSite::GetList($sitesSort, $sitesBy, array());
while($arSite = $rsSite->Fetch())
{
	$arSites[$arSite["ID"]] = $arSite["NAME"];
	if($arSite["DEF"]=="Y")
		$defSite = $arSite["ID"];
}

$arIBlockTypes=array();
$defIBlockType="news";
$rsIBlockType = CIBlockType::GetList(Array("SORT"=>"ASC"));
while($arIBlockType = $rsIBlockType->Fetch())
	if($arIBlockType = CIBlockType::GetByIDLang($arIBlockType["ID"], LANG))
		$arIBlockTypes[$arIBlockType["ID"]] = $arIBlockType["NAME"];

$arIBlocks=array("-"=>GetMessage("MAIN_ALL"));
$rsIBlock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$arCurrentValues["SITE_ID"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arIBlock = $rsIBlock->Fetch())
	$arIBlocks[$arIBlock["ID"]] = $arIBlock["NAME"];

$arSorts = array(
	"ASC" => GetMessage("CP_BSN_ORDER_ASC"),
	"DESC" => GetMessage("CP_BSN_ORDER_DESC"),
);
$arSortFields = array(
		"ACTIVE_FROM" => GetMessage("CP_BSN_ACTIVE_FROM"),
		"SORT" => GetMessage("CP_BSN_SORT"),
	);

$arProperty_N = array();
if (0 < intval($arCurrentValues['ID'])) {
	$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("IBLOCK_ID"=>$arCurrentValues["ID"], "ACTIVE"=>"Y"));
	while ($arr=$rsProp->Fetch()) {
		if($arr["PROPERTY_TYPE"]=="N")
			$arProperty_N[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}
$arPrice = array();
if (CModule::IncludeModule("catalog")) {
	$rsPrice = CCatalogGroup::GetList($v1 = "sort", $v2 = "asc");
	while ($arr = $rsPrice->Fetch())
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
} else {
	$arPrice = $arProperty_N;
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"SITE_ID" => array(
			"NAME" => GetMessage("CP_BSN_SITE_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arSites,
			"DEFAULT" => $defSite,
			"REFRESH" => "Y",
		),
		"IBLOCK_TYPE" => array(
			"NAME" => GetMessage("CP_BSN_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockTypes,
			"DEFAULT" => $defIBlockType,
			"REFRESH" => "Y",
		),
		"ID" => array(
			"NAME" => GetMessage("CP_BSN_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
		),
		"SORT_BY" => array(
			"NAME" => GetMessage("CP_BSN_SORT_BY"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
		),
		"SORT_ORDER" => array(
			"NAME" => GetMessage("CP_BSN_SORT_ORDER"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
		),
		"PRICE_CODE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
		),
		"PRICE_VAT_INCLUDE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_VAT_INCLUDE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);
?>
