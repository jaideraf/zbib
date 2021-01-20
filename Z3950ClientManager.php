<?php

    /**
     * Z39.50 client for Wikincat
     * 
     * php version 7.4
     * 
     * @category Z39.50
     * @package  Zbib
     * @author   Vítor S Rodrigues <vitor.silverio.rodrigues@gmail.com>
     * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
     * @link     https://wikincat.org/zbib/
     */

$pageSize = 10;
define('PAGESIZE', $pageSize);

$targetsList = file_get_contents("targets.json");
$targets = json_decode($targetsList, true);

for ($index = 0; $index < count($targets); $index++) {
    if (isset($_REQUEST['targets'])) {
        $targets[$index] += ['active' => ($_REQUEST['targets'][$index]=='on')];
    } else {
        $targets[$index] += ['active' => $targets[$index]['default']];
    }
}

$searchTypes = array(
    '4'     => 'título',
    '1003'  => 'autor',
    '21'    => 'assunto',
    '30'    => 'ano',
    '8'     => 'ISBN',
    '7'     => 'ISSN',
    '1016'  => 'qualquer campo'
);

$operators = array('and','or','not');

$targetsz3950  = array();
$searchType    = isset($_REQUEST['searchType'])?$_REQUEST['searchType']:'4';
$searchType2   = isset($_REQUEST['searchType2'])?$_REQUEST['searchType2']:'1003';
$searchString  = isset($_REQUEST['searchString'])?trim($_REQUEST['searchString']):'';
$searchString2 = isset($_REQUEST['searchString2'])?trim($_REQUEST['searchString2']):'';
$operator      = isset($_REQUEST['operator'])?$_REQUEST['operator']:'and';
$page          = isset($_REQUEST['page'])?$_REQUEST['page']:0;

// Executes from here only in search request
if ($searchString) { 

    if (!extension_loaded('yaz')) {
        print '<div class="alert alert-danger">
        Desculpe, "yaz.so" não foi carregado.
        </div>';
        exit;
    }

    class Target
    {
        private $_connection;
        private $_charset;
        public $title;
        public $results = array();
        public $totalRecords = 0;

        function connect($uri, $login, $syntax, $_charset)
        {
            $this->_connection = yaz_connect($uri, $login);
            yaz_syntax($this->_connection, ($syntax!=""?$syntax:"usmarc"));
            $this->_charset = $_charset;
        }
        
        function totalPages()
        {
            if ($this->totalRecords == 0) {
                return 0;
            }
            return intval(ceil($this->totalRecords / PAGESIZE));
        }

        function query($query, $page)
        {
            yaz_search($this->_connection, 'rpn', $query);
            yaz_wait();
            $error = yaz_error($this->_connection);
            if ($error) {
                echo sprintf('<div class="alert alert-danger">%s com erro: %s </div>', $this->title, $error);
            } else {
                $this->totalRecords = yaz_hits($this->_connection);
                for ($i = 1; $i <= PAGESIZE; $i++) {
                    $record = yaz_record(
                        $this->_connection, $i + (
                        intval($page)*PAGESIZE
                        ), "string" . ($this->_charset!=""?sprintf("; charset=%s,utf-8", $this->_charset):"")
                    );
                    if (empty($record)) {
                        continue;
                    }
                    array_push($this->results, $record);
                }
            }
        }

        function close()
        {
            yaz_close($this->_connection);
        }
    }

    foreach ($targets as $target) {
        if (!$target['active']) {
            continue;
        }
        $targetZ3950 = new Target();
        $targetZ3950->title = $target['title'];
        $targetZ3950->connect(
            $target['zurl'], 
            $target['userpass'], 
            $target['syntax'], 
            $target['charset']
        );
        array_push($targetsz3950, $targetZ3950);
    }

    $query = sprintf("@attr 1=%s \"%s\"", $searchType, $searchString);
    if ($searchString2) {
        $query = sprintf(
            "@%s %s @attr 1=%s \"%s\"", 
            $operator, 
            $query, 
            $searchType2, 
            $searchString2
        );
    }

    foreach ($targetsz3950 as $targetZ3950) {
        $targetZ3950->query($query, $page);
        $targetZ3950->close();
    }

}
?>