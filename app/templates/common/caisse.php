<?php
/** @var \muuska\html\HtmlCustomElement $item */

use muuska\dao\source\pdo\PDODAO;

/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\translation\LangTranslator $translator */
/** @var \muuska\renderer\template\Template $this */
/** @var \muuska\html\listing\table\Table $item */
/** @var \muuska\html\config\HtmlGlobalConfig $globalConfig */
/** @var \muuska\html\config\caller\HtmlCallerConfig $callerConfig */
/** @var \muuska\html\listing\table\Column $column */
?>
<?php echo $item->drawStartTag('table', $globalConfig, $callerConfig, 'table')?>



    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Prenom</th>
            <th scope="col">seance</th>
            <th scope="col">T.Membre</th>
            <th scope="col">Interets</th>
            <th scope="col">Global</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Nick</td>
            <td>Stone</td>
            <td>Stone</td>
            <td>
                <span class="label label-inline label-light-primary font-weight-bold">
                    Pending
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Ana</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>
                <span class="label label-inline label-light-success font-weight-bold">
                    Approved
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>Pettis</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>
                <span class="label label-inline label-light-danger font-weight-bold">
                    New
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>Pettis</td>
            <td>Jacobs</td>
            <td>Jacobs</td>
            <td>
                <span class="label label-inline label-light-danger font-weight-bold">
                    New
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row">4</th>
            <td>Larry</td>
            <td>Pettis</td>
            <td>
                <span class="label label-inline label-light-danger font-weight-bold">
                    New
                </span>
            </td>
        </tr>
    </tbody>

    <?php 

