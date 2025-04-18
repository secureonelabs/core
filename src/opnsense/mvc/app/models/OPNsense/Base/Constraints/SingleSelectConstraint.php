<?php

/**
 *    Copyright (C) 2018 Deciso B.V.
 *
 *    All rights reserved.
 *
 *    Redistribution and use in source and binary forms, with or without
 *    modification, are permitted provided that the following conditions are met:
 *
 *    1. Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *    2. Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 *    THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
 *    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 *    AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *    AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 *    OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 *    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 *    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 *    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 *    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 *    POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace OPNsense\Base\Constraints;

/**
 * Class SingleSelectConstraint Only one option might be selected
 * (when option isRequired is set, exactly one item needs to be set)
 * @package OPNsense\Base\Constraints
 */
class SingleSelectConstraint extends BaseConstraint
{
    /**
     * Executes validation
     *
     * @param $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate($validator, $attribute): bool
    {
        $node = $this->getOption('node');
        if ($node) {
            $nodeName = $node->getInternalXMLTagName();
            $parentNode = $node->getParentNode();
            $Fields = array_unique(array_merge(array($nodeName), $this->getOptionValueList('addFields')));
            $countNotEmpty = 0;
            foreach ($Fields as $fieldname) {
                if (!is_null($parentNode->$fieldname) && !$parentNode->$fieldname->isEmpty()) {
                    $countNotEmpty++;
                }
            }

            if ($countNotEmpty > 1 || ($countNotEmpty == 0 && !empty($this->getOption('isRequired', null)))) {
                $this->appendMessage($validator, $attribute);
                return false;
            }
        }
        return true;
    }
}
