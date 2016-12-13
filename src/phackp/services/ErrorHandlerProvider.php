<?php

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 22:52
 */
class ErrorHandlerProvider implements \yuxblank\phackp\api\Service
{
    protected $exceptions = [];
    protected $reportable = [];



    /**
     * @return mixed
     */
    public function getReportable()
    {
        return $this->reportable;
    }

    /**
     * @param mixed $reportable
     */
    public function setReportable(Exception $reportable)
    {
        $this->reportable = $reportable;
    }

    /**
     * @param Exception $e
     */
    public function report(Exception $e) {
        $this->exceptions[] = $e;
    }

    /**
     * @return array
     */
    public function getReports(): array
    {
        return $this->exceptions;
    }




}