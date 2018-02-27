<?php

namespace ApiBundle\Manager;

interface InterkeepassManagerInterface
{
    /**
     * @throws Exception
     */
    public function onKernelRequest();

    /**
     * Renvoit la configuration Interkeepass
     *
     * @return array
     * @throws Exception
     */
    public function getConfiguration();
}
