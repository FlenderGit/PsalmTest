<?php


namespace {
/**
 * @psalm-taint-source input
 */
function getallheaders(): array {}

    class PDOStatement {
        /**
         * @psalm-taint-source input
         */
        public function fetch() {}
    }

}