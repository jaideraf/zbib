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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Primary Meta Tags -->
    <meta charset="utf-8">
    <title>Busca Z39.50 do Wikincat</title>
    <meta name="title" content="Busca Z39.50 do Wikincat">
    <meta name="description" content="Interface de busca de registros bibliográficos do Wikincat usando o protocolo Z39.50">
    <meta name="author" content="jaideraf">
    <meta name="author" content="vitorsilverio">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://wikincat.org/zbib/">
    <meta property="og:title" content="Busca Z39.50 do Wikincat">
    <meta property="og:description" content="Interface de busca de registros bibliográficos do Wikincat usando o protocolo Z39.50">
    <meta property="og:image" content="https://wikincat.org/static-only/wikincat/img/z3950-image.png">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://wikincat.org/zbib/">
    <meta property="twitter:title" content="Busca Z39.50 do Wikincat">
    <meta property="twitter:description" content="Interface de busca de registros bibliográficos do Wikincat usando o protocolo Z39.50">
    <meta property="twitter:image" content="https://wikincat.org/static-only/wikincat/img/z3950-image.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/bfc016c9fd.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    require_once "Z3950ClientManager.php";
    require_once "formatter.php";
    ?>
    <div class="container">
        <header>
            <h3>Busca Z39.50 do Wikincat</h3>
        </header>
        <div class="col-md-12">
            <form>
                <div class="form-row">
                    <div class="form-group mr-2">
                        <label for="searchString">Encontrar</label>
                        <input type="text" class="form-control" name="searchString" id="searchString" placeholder="" value="<?= $searchString ?>">
                    </div>
                    <div class="form-group mr-2">
                        <label for="searchType">no campo</label>
                        <select name="searchType" class="form-control" id="searchType">
                            <?php
                            foreach ($searchTypes as $searchTypeValue => $searchTypeDescription) {
                                ?>
                                <option <?= $searchType==$searchTypeValue?"selected":"" ?> value="<?= $searchTypeValue ?>"><?= $searchTypeDescription ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group align-bottom d-flex align-items-end mr-2">
                        <button class="btn btn-primary" title="Executa a busca">Buscar</button>
                    </div>
                    <div class="form-group align-bottom d-flex align-items-end mr-2">
                        <a class="btn btn-link" data-toggle="collapse" data-target="#extra" title="Exibe ou esconde o operador booleano">▼ / ▲</a>
                    </div>
                    <div class="form-group align-bottom d-flex align-items-end mr-2">
                        <a class="btn btn-link" href="https://wikincat.org/zbib/" title="Vai para a página inicial">Início</a>
                    </div>
                </div>
                <div class="form-row <?= $searchString2!=''?'':'collapse' ?>" id="extra">
                    <div class="form-group mr-2">
                        <select name="operator" class="form-control" id="operator">
                            <?php
                            foreach ($operators as $o) {
                                ?>
                                <option <?= $operator==$o?"selected":"" ?> value="<?= $o ?>"><?= $o ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <input type="text" class="form-control" id="searchString2" name="searchString2" placeholder="" value="<?= $searchString2 ?>">
                    </div>
                    <div class="form-group d-flex align-items-end mr-2">
                        <label>no campo</label>
                    </div>
                    <div class="form-group mr-2">
                        <select name="searchType2" class="form-control" id="searchType2">
                            <?php
                            foreach ($searchTypes as $searchTypeValue => $searchTypeDescription) {
                                ?>
                                <option <?= $searchType2==$searchTypeValue?"selected":"" ?> value="<?= $searchTypeValue ?>"><?= $searchTypeDescription ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="alert alert-info" role="alert" ><?= $searchString!=''?'
Utilize <kbd><kbd>ctrl</kbd> + <kbd>f</kbd></kbd> para localizar dentro dos resultados.':'
<ul style="margin-bottom: 0">
    <li>Busque em até 5 bibliotecas por vez (são mais de 60 disponíveis).</li>
    <li>Utilize um <a class="alert-link" data-toggle="collapse" data-target="#extra" title="Exibe ou esconde o operador booleano">operador booleano</a> para resultados mais precisos.</li>
    <li>A remoção de acentos pode trazer mais resultados.</li>
</ul>' ?>
                </div>
                <div class="form-row mb-2">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <p style="margin-bottom: 0">
                                <a class="btn btn-link" data-toggle="collapse" data-target="#targets" title="Exibe ou esconde as bibliotecas">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                                    </svg> Bibliotecas
                                </a>
                            </p>
                        </div>
                        <div class="card-body <?= $searchString!=''?'collapse':'' ?>" id="targets">
                        <?php
                        foreach ($targets as $index => $target) {
                            ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="target<?= $index ?>" name="targets[<?= $index ?>]" <?= $target['active']?'checked':'' ?>>
                                <label class="form-check-label" for="target<?= $index ?>">
                                <img src="<?= $target['flag']?>" alt="<?= $target['title']?>" /><?= ' ' . $target['title']?></label>
                            </div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
        <?php
        $maxPages = 0;
        foreach ($targetsz3950 as $index => $target) {
            $maxPages = $maxPages<$target->totalPages()?$target->totalPages():$maxPages;
            if (count($target->results)<1) {
                continue;
            };
            ?>
            <div class="accordion" id="accordion">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"> 
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?= $index ?>">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                                </svg> <?= $target->title ?> 
                            </button>
                            <span class="badge badge-pill badge-primary float-right mt-2"><?= $target->totalRecords ?></span>
                        </h5>
                    </div>
                    <div id="collapse<?= $index ?>" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <ul class="list-group">
                                <?php
                                foreach ($target->results as $index => $result) {
                                    ?>
                                    <li class="list-group-item">
                                    <div class="col-md-12 mb-5">
                                        <div class="float-left">
                                            <span class="badge badge-pill badge-secondary">Resultado <?= $index + 1 + $pageSize * $page ?></span>
                                        </div>
                                        <div class="float-right">
                                            <button class="btn btn-link" onclick="copy(this);" title="Executa uma cópia do registro MARC"><i class="far fa-copy" alt="Ícone de copiar"> </i> Copiar registro</button> <kbd><kbd>ctrl</kbd> + <kbd>c</kbd></kbd>
                                            <form name="toWikincat" action="https://wikincat.org/w/index.php?title=Wikincat%3AMARCimporter" method="post">
                                                <input type="hidden" name="wpRunQuery" value="Preparar registro"/>
                                                <input type="hidden" name="MARCimporter[type]" value="Registro bibliográfico"/>
                                                <input type="hidden" name="MARCimporter[map_field][type]" value="true"/>
                                                <input type="hidden" name="MARCimporter[NFD][is_checkbox]" value="true"/>
                                                <input type="hidden" name="MARCimporter[record]" id="MARCimporter[record]" value="<?= formatterRecordToWikincat($result) ?>"/>
                                                <input type="hidden" name="pfRunQueryFormName" value="MARCimporter"/>
                                                <button class="btn btn-info btn-sm">Enviar para Wikincat (requer login)</button>
                                            </form>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="mt-2 mb-2">
                                        <pre><?= formatterRecordToPresentation($result) ?></pre>
                                    </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <div class="col-md-12">
        <?php
        if ($maxPages > 1) {
            $activeTargets = "";
            foreach ($targets as $index => $target) {
                if ($target['active']) {
                    $activeTargets = $activeTargets . sprintf("&targets[%s]=on", $index);
                }
            }
            $params = sprintf("?searchString=%s&searchType=%s&searchString2=%s&searchType2=%s&operator=%s%s&page=", $searchString, $searchType, $searchString2, $searchType2, $operator, $activeTargets);
            ?>
            <nav>
            <ul class="pagination flex-wrap">
                <li class="page-item">
                <a class="page-link" href="<?= $params . ($page-1) ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Anterior</span>
                </a>
                </li>
                <?php 
                for ($p = 0; $p < 10; $p++) {
                    $offsetedPage = (intval($page/10)*10)+$p+1;
                    if ($offsetedPage < $maxPages) {
                        ?>
                        <li class="page-item <?= ($page)==($offsetedPage-1)?'active':'' ?>"><a class="page-link" href="<?= $params . ($offsetedPage-1) ?>"><?= $offsetedPage ?></a></li>
                        <?php
                    }
                }
                ?>
                <li class="page-item">
                <a class="page-link" href="<?= $params . ($page+1) ?>" aria-label="Próximo">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Próximo</span>
                </a>
                </li>
            </ul>
            </nav>
            <?php
        }
        ?>
        </div>
        <br>
        <footer>
            <div class="d-flex align-items-center justify-content-center">
                <p><a href="https://wikincat.org/" target="_blank">Wikincat</a>, 2021. Creative Commons (<a href="https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR" target="_blank">CC-BY-SA 4.0</a>)</p>
            </div>
        </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>
        Array.from(document.getElementsByClassName('form-check-input')).forEach(function(c) {
            c.addEventListener('change', (e) => {
                if (e.target.checked) {
                    if(document.querySelectorAll('input[type="checkbox"].form-check-input:checked').length > 5) {
                        e.target.checked = false;
                    }
                }
            });
        });

        function copy(target) {
            try {
                let record = target.parentElement.parentElement.nextElementSibling.nextElementSibling.querySelector('pre');
                console.log(record);
                let range = document.createRange();
                range.selectNode(record);
                window.getSelection().addRange(range);
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
            } catch(e) {
                console.log(e);
            }
        }
    </script>
</body>
</html>
