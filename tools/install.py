#!/usr/bin/env python3

import getpass
import json
import os
import pymysql
import subprocess
import sys
import time

WIKI_VERSION = '1.0 Alpha 1'
IS_DEV = True

terminal_width = os.get_terminal_size().columns


def throw_error(message, detailed=None):
    print('Error: {0}'.format(message))

    try:
        with open('log.txt', 'a') as file:
            file.write('[{0}] {1} {2}\n'.format(time.time(), message, detailed))

    except IOError as e:
        print('Could not add error message to log')
        return

    print('Error message added to log')


def report_status(message, has_status=False, status=True, display_dots=True):
    global terminal_width

    if has_status:
        print(message + '... ' + str('OK' if status else 'FAILED').rjust(terminal_width - (len(message) + 5)))
    elif display_dots:
        print(message + '...')
    else:
        print(message)


def request_confirmation(message, default='y'):
    while True:
        print('{0} [{1}/{2}]'.format(message, 'Y' if default is 'y' else 'y', 'N' if default is 'n' else 'n'), end=' ')
        key = input().lower()

        if key in ('y', 'n', 'yes', 'no'):
            return key != 'n' and key != 'no'

        elif not key:
            return default == 'y'

        print('Error: Invalid input')


def request_information(message, minlength=0, maxlength=25, hide=False, default=None):
    if default:
        message = '{0} (default: {1}): '.format(message, default)
    else:
        message = '{0}: '.format(message)

    while True:
        if not hide:
            print(message, end='')
            inpt = input()
        else:
            inpt = getpass.getpass(prompt=message)

        if len(inpt) in range(minlength, maxlength + 1) or default:
            return inpt if inpt else default

        print('Invalid input. Input should be between {0} and {1} characters'.format(minlength, maxlength))


def print_horizontal_line():
    for i in range(terminal_width):
        print('-', end='')


def import_tables(cursor, db_pref, wiki_info):
    statements = []
    files = ['../ddl.sql', '../data.sql']

    result = True
    try:
        for sql_file in files:
            with open(sql_file) as file:
                current_statement = ''

                for line in file:
                    clean_line = line.strip()
                    line = line.replace('{db_prefix}', db_pref)

                    for key,value in wiki_info.items():
                        line = line.replace('{' + 'data:{0}'.format(key) + '}', value)

                    if clean_line.startswith('--') or not clean_line:
                        continue

                    if clean_line.endswith(';'):
                        statements.append(current_statement + line)
                        current_statement = ''
                    else:
                        current_statement += line

                if current_statement:
                    statements.append(current_statement)
                    current_statement = ''

            for statement in statements:
                cursor.execute(statement)

            statements = []

    except Exception as e:
        result = False
        throw_error('Could not import data.', e)
        return

    except IOError as e:
        result = False
        print('Could not open .sql files')
        return

    finally:
        report_status('Importing data', True, result)

    return True


class ChangeDirectory:
    """
    Thanks to Brian M. Hunt for this very elegant solution
    https://stackoverflow.com/questions/431684/how-do-i-cd-in-python/13197763#13197763
    """

    def __init__(self, path):
        self.path = os.path.expanduser(path)

    def __enter__(self):
        self.old_path = os.getcwd()
        os.chdir(self.path)

        report_status('Changed working directory')

    def __exit__(self, etype, value, traceback):
        os.chdir(self.old_path)


class Connection:
    def __init__(self, server, username, password, dbname):
        self.server = server
        self.username = username
        self.password = password
        self.dbname = dbname

    def connect(self):
        result = True
        try:
            self.connection = pymysql.connect(host=self.server, user=self.username, password=self.password, db=self.dbname)
            self.cursor = self.connection.cursor()

        except Exception as e:
            result = False
            throw_error('Could not connect to database', e)

        finally:
            report_status('Establishing database connection', True, result)

        return result

    def close():
        result = True
        try:
            self.connection.close()
            self.cursor.close()

        except Exception as e:
            result = False

            throw_error('Could not close database connection', e)

        finally:
            report_status('Closing database connection', True, result)

        return result


def install_libraries():
    processes = ['composer install', 'npm install']

    libraries = []

    try:
        with open('../composer.json') as file:
            content = json.load(file)

            for key, value in content['require'].items():
                libraries.append((key, value))

        with open('../package-lock.json') as file:
            content = json.load(file)

            for key, value in content['dependencies'].items():
                libraries.append((key, value['version'] if len(value['version']) < 10 else 'unknown'))

                if 'requires' in value:
                    for dep_key, dep_value in value['requires'].items():
                        libraries.append((dep_key, dep_value if len(dep_value) < 10 else 'unknown'))

    except IOError as e:
        throw_error('Could not load composer.json')
        return False

    print('The following packages will be installed:')
    print_horizontal_line()

    for library in libraries:
        print('> {0} - Version {1}'.format(library[0], library[1].replace('^', '')))

    print_horizontal_line()

    if not request_confirmation('Proceed with the installation?'):
        return False

    with ChangeDirectory('../'):
        result = True

        try:
            for process in processes:
                subprocess.run(process.split())

        except subprocess.CalledProcessError as e:
            throw_error('Could not install libraries', e)
            result = False
            return False

        finally:
            report_status('Installing libraries', True, result)

    return True


def get_path_and_url():
    return (
        '/var/www/DraiWiki' if sys.platform is 'posix' else 'C:\xampp\htdocs',
        'http://localhost/DraiWiki'
    )


def run():
    print(':: DraiWiki {0} command line installer', WIKI_VERSION)

    if IS_DEV:
        print('Warning: you are installing a development build')

    print('Welcome to DraiWiki!')

    if not request_confirmation('This script will install DraiWiki. Do you wish to proceed?'):
        report_status('Aborting')
        return

    print(':: Installing libraries')
    result = install_libraries()

    if not result:
        report_status('Aborting')
        return

    print(':: Wiki setup')
    default_path, default_url = get_path_and_url()

    wiki_info = {
        'wiki_name': request_information('Wiki name', default='My Wiki'),
        'slogan': request_information('Slogan', default='Welcome to the wiki'),
        'path': request_information('Path to wiki', default=default_path),
        'url': request_information('Url', default=default_url),
        'default_templates': request_information('Default template set', default='Hurricane'),
        'default_image_set': request_information('Default image set', default='Hurricane'),
        'default_skin_set': request_information('Default skin set', default='Hurricane')
    }

    print(':: Database server information')

    if request_confirmation('Should new tables be created?'):
        while True:
            info_host = request_information('Host', default='localhost')
            info_username = request_information('Username', default='root')
            info_password = request_information('Password', minlength=5, hide=True)
            info_dbname = request_information('Database name', default='draiwiki')

            db_pref = request_information('Table prefix', default='drai_')

            connection = Connection(info_host, info_username, info_password, info_dbname)

            if connection.connect():
                break

            print('Could not connect to database. Please try again')

        print('Database connection established')

        if not import_tables(connection.cursor, db_pref, wiki_info):
            return

    else:
        report_status('Aborting')
        return

    print('Thank you for installing DraiWiki. The installation is now complete.')


if __name__ == '__main__':
    try:
        run()

    except KeyboardInterrupt:
        report_status('\nAborting')
