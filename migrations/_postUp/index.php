<?php

$domain_expertise = include('_domainExpertise.php');
$technical_expertise = include('_technicalExpertise.php');
$programming_language = include('_programmingLanguage.php');

foreach($domain_expertise as $de) {
    $this->addSql('INSERT INTO domain_expertise (name) VALUES (?)', array($de));
}

foreach($technical_expertise as $te) {
    $this->addSql('INSERT INTO technical_expertise (name) VALUES (?)', array($te));
}

foreach($programming_language as $pl) {
    $this->addSql('INSERT INTO programming_language (name) VALUES (?)', array($pl));
}