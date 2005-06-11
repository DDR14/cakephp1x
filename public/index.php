<?PHP
//////////////////////////////////////////////////////////////////////////
// + $Id$
// +------------------------------------------------------------------+ //
// + Cake <https://developers.nextco.com/cake/>                       + //
// + Copyright: (c) 2005, Cake Authors/Developers                     + //
// + Author(s): Michal Tatarynowicz aka Pies <tatarynowicz@gmail.com> + //
// +            Larry E. Masters aka PhpNut <nut@phpnut.com>          + //
// +            Kamil Dzielinski aka Brego <brego.dk@gmail.com>       + //
// +------------------------------------------------------------------+ //
// + Licensed under The MIT License                                   + //
// + Redistributions of files must retain the above copyright notice. + //
// + See: http://www.opensource.org/licenses/mit-license.php          + //
//////////////////////////////////////////////////////////////////////////

/**
 * Purpose: Dispatch
 * The main "loop"
 * 
 * @filesource 
 * @author Cake Authors/Developers
 * @copyright Copyright (c) 2005, Cake Authors/Developers
 * @link https://developers.nextco.com/cake/wiki/Authors Authors/Developers
 * @package cake
 * @subpackage cake.public
 * @since Cake v 0.2.9
 * @version $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

session_start();

/**
  * Get Cake's root directory
  */
if (!defined('DS'))
	define ('DS', DIRECTORY_SEPARATOR);

if (!defined('ROOT'))
	define ('ROOT', dirname(dirname(__FILE__)).DS);

/**
  * Configuration, directory layout and standard libraries
  */
require (ROOT.'config/core.php');
require (ROOT.'config/paths.php');
require (ROOT.'libs/log.php');
require (ROOT.'libs/object.php');
require (ROOT.'libs/neat_array.php');
require (ROOT.'libs/inflector.php');
require (ROOT.'libs/basics.php');
require (ROOT.'libs/folder.php');

DEBUG? error_reporting(E_ALL): error_reporting(0);

$TIME_START = getMicrotime();

uses ('dispatcher', 'dbo_factory');

config ('tags', 'database');

$DB = DboFactory::make('devel');

loadModels ();

## RUN THE SCRIPT
$url = empty($_GET['url'])? null: $_GET['url'];
$DISPATCHER = new Dispatcher ();
$DISPATCHER->dispatch($url);

## CLEANUP
if (DEBUG) echo "<!-- ". round(getMicrotime() - $TIME_START, 2) ."s -->";

?>