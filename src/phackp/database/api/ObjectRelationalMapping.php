<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:42
 */

namespace yuxblank\phackp\database\api;


interface ObjectRelationalMapping
{
    /** Belongs to
     * @param $origin
     * @param $target
     * @return
     */
    public function oneToOne($origin,$target);

    /** has_many
     * @param $origin
     * @param $target
     * @return
     */
    public function oneToMany($origin,$target);

    /** Has one
     * @param $origin
     * @param $target
     * @return
     */
    public function manyToOne($origin,$target);

    /** Has Many Through
     * @param $origin
     * @param $target
     * @return
     */
    public function manyToMany($origin,$target);

}