<?php
/*
 * This file is part of the prestaForumConnectorPlugin package.
 * (c) Christophe DOLIVET <cdolivet@prestaconcept.net>
 * (c) Mikael RANDY <mrandy@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
require_once realpath( dirname(__FILE__) ).'/../bootstrap/unit.php';

$t = new lime_test(26, new lime_output_color());

$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
sfContext::createInstance($configuration);
// Récupération du SGBD (pgsql ou mysql)
$databaseManager	= new sfDatabaseManager( $configuration );
$sfDatabase 		= $databaseManager->getDatabase( sfConfig::get('app_prestaForumConnector_forumDatabaseId' ) );

$dsn 	= $sfDatabase->getParameter('dsn');
$dsn 	= explode(':',$dsn);
$dbms	= $dsn[0];


$mockUserObject		= prestaForumFactoryMock::getUserConnectorInstance();
$mockForumObject	= prestaForumFactoryMock::getForumConnectorInstance();

$t->comment('1 - Test convertMailAddressToNickName function');
	$t->is($mockForumObject->convertMailAddressToNickName('yly@prestaconcept.net'),'yly','::convertMailAddressToNickName() - yly@prestaconcept.net becomes yly');
	$t->is($mockForumObject->convertMailAddressToNickName('yly@prestaconceptnet'),'yly@prestaconceptnet','::convertMailAddressToNickName() - yly@prestaconceptnet is yly@prestaconceptnet');
	$t->is($mockForumObject->convertMailAddressToNickName('ylyconceptnet'),'ylyconceptnet','::convertMailAddressToNickName() - ylyconceptnet is ylyconceptnet');
	$t->is($mockForumObject->convertMailAddressToNickName('@hello'),'@hello','::convertMailAddressToNickName() - @hello is @hello');

$nickname 	= 'alreadys'.rand();
$email		= $nickname.'@user.fr';
$password	= 'PaSsWoRd';

$t->comment('2 - Test nickNameAlreadyUse function');
	$t->is($mockForumObject->nickNameAlreadyUse($nickname),false ,'::nickNameAlreadyUse() - The nickname '.$nickname.' is not use');
	
	$userId		= $mockUserObject->addUserTest($nickname, $email, $password);
	
	$t->is($mockForumObject->nickNameAlreadyUse($nickname),true ,'::nickNameAlreadyUse() - The nickname '.$nickname.' is use');
	
	// Suivant le SGBD, la sensibilité à la casse est différente
	if($dbms == 'pgsql')
	{
		$t->is($mockForumObject->nickNameAlreadyUse(ucfirst($nickname)),true ,'::nickNameAlreadyUse() - With the ucfirst function, the nickname '.$nickname.' is use');
		$t->is($mockForumObject->nickNameAlreadyUse(strtoupper($nickname)),true ,'::nickNameAlreadyUse() - With the strtoupper function, the nickname '.$nickname.' is use');
	}
	else
	{
		$t->is($mockForumObject->nickNameAlreadyUse(ucfirst($nickname)),false ,'::nickNameAlreadyUse() - With the ucfirst function, the nickname '.$nickname.' is not use');
		$t->is($mockForumObject->nickNameAlreadyUse(strtoupper($nickname)),false ,'::nickNameAlreadyUse() - With the strtoupper function, the nickname '.$nickname.' is not use');
	}
	
$t->comment('3 - Test convertToForumNickName function');
	$t->is($mockForumObject->convertToForumNickName('yly@prestaconcept.net', 0, 0),'yly','::convertToForumNickName() - yly@prestaconcept.net becomes yly');
	$t->isnt($mockForumObject->convertToForumNickName('yl', 0, 0),'yl','::convertToForumNickName() - yl is too short so the nickname is different');
	$t->isnt($mockForumObject->convertToForumNickName( $nickname , 0, 0), $nickname, '::convertToForumNickName() - '. $nickname .' already exist so the nickname is different');

$t->comment('4 - Check if the project user is added');
	$t->ok( strlen( $mockUserObject->getUserNickName($userId) ) > 0,'::getUserNickName() - Project user name can be retrieved');
	$t->is($mockUserObject->getUserEmail($userId),$email,'::getUserEmail() - Project user mail is '. $email);
	$t->ok($mockUserObject->isUserEnabled($userId),'::getUserEnabled() - Project user is enabled');

$t->comment('5 - Check if the forum user is added');
	$t->ok($mockForumObject->projectUserExist($userId),'::projectUserExist() - User exist');
	$t->ok(!$mockForumObject->projectUserExist(100000000),'::projectUserExist() - User not exist');
	$t->isnt($mockForumObject->getForumUserIdFromProjectUserId($userId),false,'::getForumUserIdFromProjectUserId() return an id');
	$t->is($mockForumObject->getForumUserIdFromProjectUserId(1000000),false,'::getForumUserIdFromProjectUserId() is false');
	$t->is($mockForumObject->getUserNickName($userId), $nickname ,'::getUserNickName() - User is '. $nickname);
	$t->isnt($mockForumObject->getUserNickName($userId + 1),$nickname,'::getUserNickName() - User is not '. $nickname);
	$t->is($mockForumObject->getProjectUserIdFromForumUserId($mockForumObject->getForumUserIdFromProjectUserId($userId)),$userId,'::getProjectUserIdFromForumUserId() - that correspond');

$t->comment('6 - Sign In');

	$forumUserId = $mockForumObject->getForumUserIdFromProjectUserId($userId);
	$mockForumObject->signIn($userId);

	$t->ok($mockForumObject->isSignedIn($forumUserId),'::signIn() - user is sign in');

$t->comment('7 - Sign Out');
	
	$mockForumObject->signOut($userId);
	$t->ok(!$mockForumObject->isSignedIn($forumUserId),'::signOut() - user is sign out');

$t->comment('8 - Enable and disable User');

	$t->ok($mockForumObject->forumUserIsEnabled($forumUserId),'::forumUserIsEnabled() - User is Enabled');
	
	$mockForumObject->disableForumUser( $userId );
	
	$t->ok(!$mockForumObject->forumUserIsEnabled($forumUserId),'::disableForumUser() - disable the user');
	
	$mockForumObject->enableForumUser( $userId );
	
	$t->ok($mockForumObject->forumUserIsEnabled($forumUserId),'::enableForumUser() - enable the user');

//$t->comment('9 - Delete a sfGuardUser');
//$user->delete();
//$t->ok($mockForumObject->forumUserIsEnabled($forumUserId),'project user is deleted => forum user is disabled');
