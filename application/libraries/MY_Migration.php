<?php

class MY_Migration extends CI_Migration
{
    public function __construct(array $config = array())
    {
        parent::__construct($config);
    }

    /**
     * drop migration version
     * @param $target_version
     * @return int|string
     */
    public function drop_version($target_version)
    {
        // Note: We use strings, so that timestamp versions work on 32-bit systems
        $current_version = $this->_get_version();

        if ($this->_migration_type === 'sequential') {
            $target_version = sprintf('%03d', $target_version);
        } else {
            $target_version = (string)$target_version;
        }

        $migrations = $this->find_migrations();

        if ($target_version > 0 && !isset($migrations[$target_version])) {
            $this->_error_string = sprintf($this->lang->line('migration_not_found'), $target_version);
            return FALSE;
        }

        $method = 'down';

        $file = $migrations[$target_version];
        $number = $target_version;
        include_once($file);
        $class = 'Migration_' . ucfirst(
                strtolower(
                    $this->_get_migration_name(basename($file, '.php'))
                ));

        // Validate the migration file structure
        if (!class_exists($class, FALSE)) {
            $this->_error_string = sprintf(
                $this->lang->line('migration_class_doesnt_exist'),
                $class
            );
            return FALSE;
        } elseif (!is_callable(array($class, $method))) {
            $this->_error_string = sprintf(
                $this->lang->line('migration_missing_' . $method . '_method'),
                $class
            );
            return FALSE;
        }

        log_message('debug',
            'Migrating ' . $method .
            ' from version ' . $current_version .
            ' to version ' . $number
        );

        $class = new $class;
        call_user_func(array($class, $method));

        log_message('debug', 'Finished migrating to ' . $current_version);
        return $current_version;
    }

}