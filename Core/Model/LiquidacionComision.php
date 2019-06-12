<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2019 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Model;

use FacturaScripts\Dinamic\Model\FacturaCliente;

/**
 * List of Commissions Settlement.
 *
 * @author Artex Trading s.a.   <jcuello@artextrading.com>
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 */
class LiquidacionComision extends Base\ModelClass
{

    use Base\ModelTrait;

    /**
     * id of agent.
     *
     * @var string
     */
    public $codagente;

    /**
     * id of exercise.
     *
     * @var string
     */
    public $codejercicio;

    /**
     * Date of creation of the settlement.
     *
     * @var string
     */
    public $fecha;

    /**
     * id of generate invoice.
     *
     * @var string
     */
    public $idfactura;

    /**
     * Total amount of the commission settlement.
     *
     * @var double
     */
    public $total;

    /**
     * Reset the values of all model properties.
     */
    public function clear()
    {
        parent::clear();
        $this->fecha = date('d-m-Y');
        $this->total = 0.00;
    }

    /**
     * Calculate the total commission amount of a settlement
     *
     * @param int $settle
     */
    public function calculateTotalCommission($settle)
    {
        $sql = 'UPDATE ' . self::tableName()
            . ' SET total = COALESCE('
                . '(SELECT SUM(neto * porcomision / 100)'
                .  ' FROM ' . FacturaCliente::tableName()
                . ' WHERE idliquidacion = ' . $settle . ')'
            . ',0)'
            . ' WHERE idliquidacion = ' . $settle;

        return self::$dataBase->exec($sql);
    }

    /**
     * This function is called when creating the model table. Returns the SQL
     * that will be executed after the creation of the table. Useful to insert values
     * default.
     *
     * @return string
     */
    public function install()
    {
        /// needed dependencies
        new Agente();
        new Ejercicio();
        new FacturaProveedor();

        return parent::install();
    }

    /**
     * Returns the name of the column that is the model's primary key.
     *
     * @return string
     */
    public static function primaryColumn()
    {
        return 'idliquidacion';
    }

    /**
     * Returns the name of the table that uses this model.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'liquidacioncomision';
    }

    /**
     * Returns the url where to see / modify the data.
     *
     * @param string $type
     * @param string $list
     *
     * @return string
     */
    public function url(string $type = 'auto', string $list = 'List')
    {
        return parent::url($type, 'ListAgente?activetab=' . $list);
    }
}