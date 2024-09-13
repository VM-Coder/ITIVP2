<?php

    session_start();
    class Validator {
        private array $data;
        private array $patterns;
        private array $messages;
        public string $last_message;
        /**
         * @param array $data - массив строк, которые нужно проверить
         * @param array $patterns - массив регулярных выражений для этих строк
         * @param array $messages - массив сообщений об ошибке
         */
        public function __construct(array $data, array $patterns, array $messages){
            $this->data = $data;
            $this->patterns = $patterns;
            $this->messages = $messages;
            $this->last_message = '';
        }
        public function validate(){
            try {
                if (count($this->data) != count($this->patterns) || count($this->patterns) != count($this->messages))
                    throw new Exception('Массивы должны соедржать одинаковое количество элементов');
                
                for ($i = 0; $i < count($this->data); $i++){
                    if (!preg_match($this->patterns[$i], $this->data[$i])){
                        $this->last_message = $this->messages[$i];
                        return false;
                    }            
                }

                return true;
            }
            catch (Exception $ex){
                file_put_contents(
                    '../error-log.log',
                    $ex->getMessage() + '\n' + $ex->getTraceAsString(),
                    FILE_APPEND
                );
            }        
        }
    }