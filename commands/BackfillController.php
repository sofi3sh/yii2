<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\models\AuthItem;
use \app\models\AuthItemChild;
use \app\models\User;
use \app\models\Product;
use \app\models\ProductOption;
use \app\models\Status;
use \app\models\NextStatusOrder;
use \app\models\FileType;
use \app\models\PrintedFormFormula;
use \app\models\InstructionSourceMessage;
use \app\models\StatusCommentReason;

class BackfillController extends Controller
{    
    public function actionDatabase()
    {
        $authManager = Yii::$app->authManager;

        // Create admin role
        $adminRole = $authManager->getRole(AuthItem::ROLE_ADMIN);
        if (!$adminRole) {
            $adminRole = $authManager->createRole(AuthItem::ROLE_ADMIN);
            $authManager->add($adminRole);
            echo AuthItem::ROLE_ADMIN . " role was created\n";
        }

        $customerRole = $authManager->getRole(AuthItem::ROLE_CUSTOMER);
        if (!$customerRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_CUSTOMER, 
                [
                    'en-US' => 'Customer',
                    'uk' => 'Клієнт'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_CUSTOMER . " role was created\n";
        }

        $designDepartmentRole = $authManager->getRole(AuthItem::ROLE_DESIGN_DEPARTMENT);
        if (!$designDepartmentRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_DESIGN_DEPARTMENT, 
                [
                    'en-US' => 'Design Department',
                    'uk' => 'Конструкторський відділ'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_DESIGN_DEPARTMENT . " role was created\n";
        }

        $calculationDepartmentRole = $authManager->getRole(AuthItem::ROLE_CALCULATION_DEPARTMENT);
        if (!$calculationDepartmentRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_CALCULATION_DEPARTMENT, 
                [
                    'en-US' => 'Сalculation Department',
                    'uk' => 'Розрахунковий відділ'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_CALCULATION_DEPARTMENT . " role was created\n";
        }
        
        $technologyDepartmentRole = $authManager->getRole(AuthItem::ROLE_TECHNOLOGY_DEPARTMENT);
        if (!$technologyDepartmentRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_TECHNOLOGY_DEPARTMENT, 
                [
                    'en-US' => 'Department of Technology',
                    'uk' => 'Отдел технолога'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_TECHNOLOGY_DEPARTMENT . " role was created\n";
        }
        
        $designerRole = $authManager->getRole(AuthItem::ROLE_DESIGNER_DEPARTMENT);
        if (!$designerRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_DESIGNER_DEPARTMENT, 
                [
                    'en-US' => 'Designer',
                    'uk' => 'Конструктор'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_DESIGNER_DEPARTMENT . " role was created\n";
        }
        
        $pricingRole = $authManager->getRole(AuthItem::ROLE_PRICING_DEPARTMENT);
        if (!$pricingRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_PRICING_DEPARTMENT, 
                [
                    'en-US' => 'Pricing',
                    'uk' => 'Розрахунок ціни'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_PRICING_DEPARTMENT . " role was created\n";
        }
        
        $technologistRole = $authManager->getRole(AuthItem::ROLE_TECHNOLOGIST_DEPARTMENT);
        if (!$technologistRole) {
            AuthItem::addPermission(
                AuthItem::ROLE_TECHNOLOGIST_DEPARTMENT, 
                [
                    'en-US' => 'Technologist',
                    'uk' => 'Технолог'
                ], 
                AuthItem::SCENARIO_CREATE_ROLE
            );
            echo AuthItem::ROLE_TECHNOLOGIST_DEPARTMENT . " role was created\n";
        }
        
        // Create permission to manage users
        $menageUsersPermissions = $authManager->getPermission(AuthItem::PERMISSION_USERS_MANAGE);
        if (!$menageUsersPermissions) {
            $menageUsersPermissions = AuthItem::addPermission(AuthItem::PERMISSION_USERS_MANAGE, [
                'en-US' => 'User management',
                'uk' => 'Управління користувачами'
            ]);
            echo AuthItem::PERMISSION_USERS_MANAGE . " permission was created\n";
        }

        // Create permission for adding new users
        $createUserPermissions = $authManager->getPermission(AuthItem::PERMISSION_USER_CREATE);
        if (!$createUserPermissions) {
            $createUserPermissions = AuthItem::addPermission(AuthItem::PERMISSION_USER_CREATE, [
                'en-US' => 'Create a new user',
                'uk' => 'Створити нового користувача'
            ]);
            echo AuthItem::PERMISSION_USER_CREATE . " permission was created\n";
        }

        // Create permission for adding new user roles
        $createUserPermissions = $authManager->getPermission(AuthItem::PERMISSION_AUTH_CREATE_ROLE);
        if (!$createUserPermissions) {
            $createUserPermissions = AuthItem::addPermission(AuthItem::PERMISSION_AUTH_CREATE_ROLE, [
                'en-US' => 'Create a new user role',
                'uk' => 'Створити нову групу користувачів'
            ]);
            echo AuthItem::PERMISSION_AUTH_CREATE_ROLE .  " permission was created\n";
        }

        // Create permission for changing user's permissions
        $createUserPermissions = $authManager->getPermission(AuthItem::PERMISSION_AUTH_MANAGE_PERMISSIONS);
        if (!$createUserPermissions) {
            $createUserPermissions = AuthItem::addPermission(AuthItem::PERMISSION_AUTH_MANAGE_PERMISSIONS, [
                'en-US' => 'Change permissions',
                'uk' => 'Змінити права доступу'
            ]);
            echo AuthItem::PERMISSION_AUTH_MANAGE_PERMISSIONS . " permission was created\n";
        }

        // Create permission to manage products
        $createUserPermissions = $authManager->getPermission(AuthItem::PERMISSION_PRODUCT_MANAGE);
        if (!$createUserPermissions) {
            $createUserPermissions = AuthItem::addPermission(AuthItem::PERMISSION_PRODUCT_MANAGE, [
                'en-US' => 'Product management',
                'uk' => 'Управління продуктами'
            ]);
            echo AuthItem::PERMISSION_PRODUCT_MANAGE .  " permission was created\n";
        }

        // Create permission for creating a new product
        $createProduct = $authManager->getPermission(AuthItem::PERMISSION_PRODUCT_CREATE);
        if (!$createProduct) {
            $createProduct = AuthItem::addPermission(AuthItem::PERMISSION_PRODUCT_CREATE, [
                'en-US' => 'Create a product',
                'uk' => 'Створити продукт'
            ]);
            echo AuthItem::PERMISSION_PRODUCT_CREATE . " permission was created\n";
        }
        
        // Create permission for creating a new product option
        $createProductOption = $authManager->getPermission(AuthItem::PERMISSION_PRODUCT_OPTION_CREATE);
        if (!$createProductOption) {
            $createProductOption = AuthItem::addPermission(AuthItem::PERMISSION_PRODUCT_OPTION_CREATE, [
                'en-US' => 'Create a product option',
                'uk' => 'Створити опцію для продукту'
            ]);
            echo AuthItem::PERMISSION_PRODUCT_OPTION_CREATE . " permission was created\n";
        }

        // Create permission for creating a new product option
        $viewProductList = $authManager->getPermission(AuthItem::PERMISSION_VIEW_PRODUCT_LIST);
        if (!$viewProductList) {
            $viewProductList = AuthItem::addPermission(AuthItem::PERMISSION_VIEW_PRODUCT_LIST, [
                'en-US' => 'View a list of products',
                'uk' => 'Переглянути список продуктів'
            ]);
            echo AuthItem::PERMISSION_VIEW_PRODUCT_LIST . " permission was created\n";
        }

        // Create permission for creating a new product option
        $viewFilesSection = $authManager->getPermission(AuthItem::PERMISSION_FILES_SECTION);
        if (!$viewFilesSection) {
            $viewFilesSection = AuthItem::addPermission(AuthItem::PERMISSION_FILES_SECTION, [
                'en-US' => 'Access to Files section',
                'uk' => 'Доступ до файлів'
            ]);
            echo AuthItem::PERMISSION_FILES_SECTION . " permission was created\n";
        }

        // Assign permissions to Admin role
        $permissionsToAssign = [
            AuthItem::PERMISSION_USERS_MANAGE,
            AuthItem::PERMISSION_USER_CREATE,
            AuthItem::PERMISSION_AUTH_CREATE_ROLE,
            AuthItem::PERMISSION_AUTH_MANAGE_PERMISSIONS,
            AuthItem::PERMISSION_PRODUCT_MANAGE,
            AuthItem::PERMISSION_PRODUCT_CREATE,
            AuthItem::PERMISSION_PRODUCT_OPTION_CREATE,
            AuthItem::PERMISSION_VIEW_PRODUCT_LIST,
            AuthItem::PERMISSION_FILES_SECTION,
        ];
        AuthItemChild::assignPermissionsToRole(AuthItem::ROLE_ADMIN, $permissionsToAssign);
        
        // Create a user with Admin role
        $adminUser = User::findByUsername('admin');
        if (!$adminUser) {
            $adminUser = new User([
                'full_name' => 'admin',
                'username' => 'admin',
                'password' => 'metalpark',
                'password_confirm' => 'metalpark',
                'email' => 'test@mail.com',
                'role' => AuthItem::ROLE_ADMIN
            ]);
            $adminUser->scenario = User::SCENARIO_REGISTER;
            $adminUser->save();
            echo "A user with role " . AuthItem::ROLE_ADMIN . " was created\n";
        }

        // Create products
        $internalDrainage = Product::find()->where(['product_key' => Product::INTERNAL_DRAINAGE])->one();
        if (!$internalDrainage) {
            $internalDrainage = new Product([
                'product_key' => Product::INTERNAL_DRAINAGE,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage',
                        'uk' => 'Внутрішній водовідвід'
                    ]
                ]
            ]);
            $internalDrainage->save();
            echo "The product with the " . Product::INTERNAL_DRAINAGE . " key was created\n";
        }

        $bridgeTray = Product::find()->where(['product_key' => Product::BRIDGE_TRAY])->one();
        if (!$bridgeTray) {
            $bridgeTray = new Product([
                'product_key' => Product::BRIDGE_TRAY,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Bridge Tray',
                        'uk' => 'Мостовий лоток'
                    ]
                ]
            ]);
            $bridgeTray->save();
            echo "The product with the " . Product::BRIDGE_TRAY . " key was created\n";
        }

        // Create Product Options
        $bridgeTrayFile = ProductOption::find()->where(['option_key' => ProductOption::BRIDGE_TRAY_FILE])->one();
        if (!$bridgeTrayFile) {
            $bridgeTrayFile = new ProductOption([
                'option_key' => ProductOption::BRIDGE_TRAY_FILE,
                'product_id' => $bridgeTray->id,
                'option_type' => ProductOption::getOptionTypeByName('file'),
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Attach File',
                        'uk' => 'Додати файл'
                    ]
                ]
            ]);
            $bridgeTrayFile->save();
            echo "The product option with the " . ProductOption::BRIDGE_TRAY_FILE . " key was created\n";
        }

        $heightMin = ProductOption::find()->where(['option_key' => ProductOption::HEIGHT_MIN])->one();
        if (!$heightMin) {
            $heightMin = new ProductOption([
                'option_key' => ProductOption::HEIGHT_MIN,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'measurement_unit' => 'mm',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'H min',
                        'uk' => 'H min'
                    ]
                ]
            ]);
            $heightMin->save();
            echo "The product option with the " . ProductOption::HEIGHT_MIN . " key was created\n";
        }

        $heightMax = ProductOption::find()->where(['option_key' => ProductOption::HEIGHT_MAX])->one();
        if (!$heightMax) {
            $heightMax = new ProductOption([
                'option_key' => ProductOption::HEIGHT_MAX,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'measurement_unit' => 'mm',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'H max',
                        'uk' => 'H max'
                    ]
                ]
            ]);
            $heightMax->save();
            echo "The product option with the " . ProductOption::HEIGHT_MAX . " key was created\n";
        }

        $hydraulicSplit = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_SPLIT])->one();
        if (!$hydraulicSplit) {
            $hydraulicSplit = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_SPLIT,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Hydraulic section',
                        'uk' => 'Гідравлічне січення'
                    ]
                ]
            ]);
            $hydraulicSplit->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_SPLIT . " key was created\n";
        }

        $trayLength = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_TRAY_LENGTH])->one();
        if (!$trayLength) {
            $trayLength = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_TRAY_LENGTH,
                'product_id' => $internalDrainage->id,
                'option_type' => 1,
                'measurement_unit' => 'mm',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Channel Length',
                        'uk' => 'Довжина лотка'
                    ]
                ]
            ]);
            $trayLength->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_TRAY_LENGTH . " key was created\n";
        }

        $traySlope = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_TRAY_SLOPE])->one();
        if (!$traySlope) {
            $traySlope = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_TRAY_SLOPE,
                'product_id' => $internalDrainage->id,
                'option_type' => 1,
                'value' => '0.5',
                'measurement_unit' => '%',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Channel Slope',
                        'uk' => 'Ухил лотка'
                    ]
                ]
            ]);
            $traySlope->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_TRAY_SLOPE . " key was created\n";
        }
        
        $hydraulicConnectionType = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_CONNECTION_TYPE])->one();
        if (!$hydraulicConnectionType) {
            $hydraulicConnectionType = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_CONNECTION_TYPE,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Type Of Connection',
                        'uk' => "Тип з'єднання"
                    ]
                ]
            ]);
            $hydraulicConnectionType->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_CONNECTION_TYPE . " key was created\n";
        }

        $hydraulicFlange = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_FLANGE])->one();
        if (!$hydraulicFlange) {
            $hydraulicFlange = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_FLANGE,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicConnectionType->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Flange',
                        'uk' => 'Фланець'
                    ]
                ]
            ]);
            $hydraulicFlange->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_FLANGE . " key was created\n";
        }

        $hydraulicUnderWelding = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_UNDER_WELDING])->one();
        if (!$hydraulicUnderWelding) {
            $hydraulicUnderWelding = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_UNDER_WELDING,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicConnectionType->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Welding',
                        'uk' => 'Під зварку'
                    ]
                ]
            ]);
            $hydraulicUnderWelding->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_UNDER_WELDING . " key was created\n";
        }

        $hydraulicDrainageType = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_DRAINAGE_TYPE])->one();
        if (!$hydraulicDrainageType) {
            $hydraulicDrainageType = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_DRAINAGE_TYPE,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Type Of Drainage',
                        'uk' => 'Тип водовідведення'
                    ]
                ]
            ]);
            $hydraulicDrainageType->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_DRAINAGE_TYPE . " key was created\n";
        }

        $hydraulictTubularOutput = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_TUBULAR_OUTPUT])->one();
        if (!$hydraulictTubularOutput) {
            $hydraulictTubularOutput = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_TUBULAR_OUTPUT,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicDrainageType->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Tubular Output',
                        'uk' => 'Трубний випуск'
                    ]
                ]
            ]);
            $hydraulictTubularOutput->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_TUBULAR_OUTPUT . " key was created\n";
        }

        $hydraulictLadderUnderCut = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_LADDER_UNDER_CUT])->one();
        if (!$hydraulictLadderUnderCut) {
            $hydraulictLadderUnderCut = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_LADDER_UNDER_CUT,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicDrainageType->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Ladder Under The Cut',
                        'uk' => 'Трап під врізку'
                    ]
                ]
            ]);
            $hydraulictLadderUnderCut->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_LADDER_UNDER_CUT . " key was created\n";
        }

        $hydraulicTrapopruyamok = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_TRAPOPRUYAMOK])->one();
        if (!$hydraulicTrapopruyamok) {
            $hydraulicTrapopruyamok = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_TRAPOPRUYAMOK,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicDrainageType->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Trapopruyamok',
                        'uk' => 'Трапоприямок'
                    ]
                ]
            ]);
            $hydraulicTrapopruyamok->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_TRAPOPRUYAMOK . " key was created\n";
        }

        $hydraulicReleaseDirection = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION])->one();
        if (!$hydraulicReleaseDirection) {
            $hydraulicReleaseDirection = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Release Direction',
                        'uk' => 'Напрямок випуску'
                    ]
                ]
            ]);
            $hydraulicReleaseDirection->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_DIRECTION . " key was created\n";
        }

        $hydraulicReleaseDirectionLeft = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_LEFT])->one();
        if (!$hydraulicReleaseDirectionLeft) {
            $hydraulicReleaseDirectionLeft = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_LEFT,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleaseDirection->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'To The Left',
                        'uk' => 'Вліво'
                    ]
                ]
            ]);
            $hydraulicReleaseDirectionLeft->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_DIRECTION_LEFT . " key was created\n";
        }

        $hydraulicReleaseDirectionRight = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_RIGHT])->one();
        if (!$hydraulicReleaseDirectionRight) {
            $hydraulicReleaseDirectionRight = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_RIGHT,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleaseDirection->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'To The Right',
                        'uk' => 'Вправо'
                    ]
                ]
            ]);
            $hydraulicReleaseDirectionRight->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_DIRECTION_RIGHT . " key was created\n";
        }

        $hydraulicReleaseDirectionStraight = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_STRAIGHT])->one();
        if (!$hydraulicReleaseDirectionStraight) {
            $hydraulicReleaseDirectionStraight = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_STRAIGHT,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleaseDirection->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Straight',
                        'uk' => 'Прямо'
                    ]
                ]
            ]);
            $hydraulicReleaseDirectionStraight->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_DIRECTION_STRAIGHT . " key was created\n";
        }

        $hydraulicReleaseDirectionVertically = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_VERTICALLY])->one();
        if (!$hydraulicReleaseDirectionVertically) {
            $hydraulicReleaseDirectionVertically = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_DIRECTION_VERTICALLY,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleaseDirection->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Vertically',
                        'uk' => 'Вертикально'
                    ]
                ]
            ]);
            $hydraulicReleaseDirectionVertically->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_DIRECTION_VERTICALLY . " key was created\n";
        }

        $hydraulicReleasePlacement = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT])->one();
        if (!$hydraulicReleasePlacement) {
            $hydraulicReleasePlacement = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Placement Of The Release',
                        'uk' => 'Pозташування випуску'
                    ]
                ]
            ]);
            $hydraulicReleasePlacement->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_PLACEMENT . " key was created\n";
        }

        $hydraulicReleasePlacementAtTheEnd = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END])->one();
        if (!$hydraulicReleasePlacementAtTheEnd) {
            $hydraulicReleasePlacementAtTheEnd = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleasePlacement->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'At the end',
                        'uk' => 'В кінці'
                    ]
                ]
            ]);
            $hydraulicReleasePlacementAtTheEnd->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END . " key was created\n";
        }

        $hydraulicReleasePlacementAtTheEnd = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END])->one();
        if (!$hydraulicReleasePlacementAtTheEnd) {
            $hydraulicReleasePlacementAtTheEnd = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END,
                'product_id' => $internalDrainage->id,
                'parent_id' => $hydraulicReleasePlacement->id,
                'option_type' => 4,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'At the end',
                        'uk' => 'В кінці'
                    ]
                ]
            ]);
            $hydraulicReleasePlacementAtTheEnd->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_RELEASE_PLACEMENT_END . " key was created\n";
        }

        $hydraulicWaterSeal = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_WATER_SEAL])->one();
        if (!$hydraulicWaterSeal) {
            $hydraulicWaterSeal = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_WATER_SEAL,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Water Seal',
                        'uk' => 'Гідрозатвор'
                    ]
                ]
            ]);
            $hydraulicWaterSeal->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_WATER_SEAL . " key was created\n";
        }

        $hydraulicWaterSealAndCatcher = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_WATER_SEAL_AND_CATCHER])->one();
        if (!$hydraulicWaterSealAndCatcher) {
            $hydraulicWaterSealAndCatcher = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_WATER_SEAL_AND_CATCHER,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Water Seal And Basket',
                        'uk' => 'Гідрозатвор і вловлювач'
                    ]
                ]
            ]);
            $hydraulicWaterSealAndCatcher->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_WATER_SEAL_AND_CATCHER . " key was created\n";
        }

        $noEndLidInBeginningOfLine = ProductOption::find()->where(['option_key' => ProductOption::NO_END_LID_IN_BEGINNING_OF_LINE])->one();
        if (!$noEndLidInBeginningOfLine) {
            $noEndLidInBeginningOfLine = new ProductOption([
                'option_key' => ProductOption::NO_END_LID_IN_BEGINNING_OF_LINE,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Take away end lid in the beginning of the line',
                        'uk' => 'Забрати торцеву кришку на початку лінії'
                    ]
                ]
            ]);
            $noEndLidInBeginningOfLine->save();
            echo "The product option with the " . ProductOption::NO_END_LID_IN_BEGINNING_OF_LINE . " key was created\n";
        }

        $hydraulicGrille = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE])->one();
        if (!$hydraulicGrille) {
            $hydraulicGrille = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Grating',
                        'uk' => 'Решітка'
                    ]
                ]
            ]);
            $hydraulicGrille->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE . " key was created\n";
        }

        $hydraulicGrilleType = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE])->one();
        if (!$hydraulicGrilleType) {
            $hydraulicGrilleType = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE,
                'product_id' => $internalDrainage->id,
                'option_type' => 3,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Type of Grating',
                        'uk' => 'Тип решітки'
                    ]
                ]
            ]);
            $hydraulicGrilleType->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE . " key was created\n";
        }

        $hydraulicGrilleTypePerforated = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_PERFORATED])->one();
        if (!$hydraulicGrilleTypePerforated) {
            $hydraulicGrilleTypePerforated = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_PERFORATED,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Perforated',
                        'uk' => 'Перфорована'
                    ]
                ]
            ]);
            $hydraulicGrilleTypePerforated->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE_PERFORATED . " key was created\n";
        }

        $hydraulicGrilleTypeCellular = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_CELLULAR])->one();
        if (!$hydraulicGrilleTypeCellular) {
            $hydraulicGrilleTypeCellular = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_CELLULAR,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Mesh',
                        'uk' => 'Чарункова'
                    ]
                ]
            ]);
            $hydraulicGrilleTypeCellular->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE_CELLULAR . " key was created\n";
        }

        $hydraulicGrilleTypeSlit = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_SLIT])->one();
        if (!$hydraulicGrilleTypeSlit) {
            $hydraulicGrilleTypeSlit = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_SLIT,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Slotted',
                        'uk' => 'Щілинна'
                    ]
                ]
            ]);
            $hydraulicGrilleTypeSlit->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE_SLIT . " key was created\n";
        }

        $hydraulicGrilleTypeNonStandard = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_NONSTANDARD])->one();
        if (!$hydraulicGrilleTypeNonStandard) {
            $hydraulicGrilleTypeNonStandard = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_NONSTANDARD,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Non-standard',
                        'uk' => 'Нестандартна'
                    ]
                ]
            ]);
            $hydraulicGrilleTypeNonStandard->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE_NONSTANDARD . " key was created\n";
        }

        $hydraulicGrilleTypeWithoutGrille = ProductOption::find()
            ->where([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE
            ])
            ->one();
        if (!$hydraulicGrilleTypeWithoutGrille) {
            $hydraulicGrilleTypeWithoutGrille = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Without Grating',
                        'uk' => 'Без решітки'
                    ]
                ]
            ]);
            $hydraulicGrilleTypeWithoutGrille->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE . " key was created\n";
        }

        $hydraulicGrilleNonStandardFile = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE])->one();
        if (!$hydraulicGrilleNonStandardFile) {
            $hydraulicGrilleNonStandardFile = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE,
                'product_id' => $internalDrainage->id,
                'option_type' => ProductOption::getOptionTypeByName('file'),
                'parent_id' => $hydraulicGrilleTypeNonStandard->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Attach File',
                        'uk' => 'Додати Файл'
                    ]
                ]
            ]);
            $hydraulicGrilleNonStandardFile->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE . " key was created\n";
        }

        $hydraulicGrilleMeshAntiSlip = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_MASH_ANTI_SLIP])->one();
        if (!$hydraulicGrilleMeshAntiSlip) {
            $hydraulicGrilleMeshAntiSlip = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_MASH_ANTI_SLIP,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Mesh anti-slip',
                        'uk' => 'Чарункова-антиковазка'
                    ]
                ]
            ]);
            $hydraulicGrilleMeshAntiSlip->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_MASH_ANTI_SLIP . " key was created\n";
        }

        $hydraulicGrilleLamellar = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_LAMELLAR])->one();
        if (!$hydraulicGrilleLamellar) {
            $hydraulicGrilleLamellar = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_LAMELLAR,
                'product_id' => $internalDrainage->id,
                'option_type' => 4,
                'parent_id' => $hydraulicGrilleType->id,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Lamellar',
                        'uk' => 'Пластинчаста'
                    ]
                ]
            ]);
            $hydraulicGrilleLamellar->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_LAMELLAR . " key was created\n";
        }

        $hydraulicGrilleAdjustmentByCustomer = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER])->one();
        if (!$hydraulicGrilleAdjustmentByCustomer) {
            $hydraulicGrilleAdjustmentByCustomer = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Grate adjustment by the customer',
                        'uk' => 'Підрізка решіток замовником'
                    ]
                ]
            ]);
            $hydraulicGrilleAdjustmentByCustomer->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER . " key was created\n";
        }

        $hydraulicGrilleAdjustmentByManufacture = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE])->one();
        if (!$hydraulicGrilleAdjustmentByManufacture) {
            $hydraulicGrilleAdjustmentByManufacture = new ProductOption([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE,
                'product_id' => $internalDrainage->id,
                'option_type' => 2,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Grate adjustment by the manufacturer',
                        'uk' => 'Підрізка решіток на виробництві'
                    ]
                ]
            ]);
            $hydraulicGrilleAdjustmentByManufacture->save();
            echo "The product option with the " . ProductOption::HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE . " key was created\n";
        }

        $statusDraft = Status::find()->where(['key' => Status::DRAFT])->one();
        if (!$statusDraft) {
            $statusDraft = new Status([
                'key' => Status::DRAFT,
                'order' => 10,
                'color' => '#3cbad1',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Draft',
                        'uk' => 'Чорновик'
                    ]
                ]
            ]);
            $statusDraft->save();
            echo "The status with the " . Status::DRAFT . " key was created\n";
        }

        $statusTS = Status::find()->where(['key' => Status::TS])->one();
        if (!$statusTS) {
            $statusTS = new Status([
                'key' => Status::TS,
                'order' => 20,
                'color' => '#17d22a',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Engineering of TS',
                        'uk' => 'Розробка ТР'
                    ]
                ]
            ]);
            $statusTS->save();
            echo "The status with the " . Status::TS . " key was created\n";
        }

        $statusCheckTech = Status::find()->where(['key' => Status::CHECK_TECH])->one();
        if (!$statusCheckTech) {
            $statusCheckTech = new Status([
                'key' => Status::CHECK_TECH,
                'order' => 30,
                'color' => '#e6d925',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Check-tech',
                        'uk' => 'Контроль технолога'
                    ]
                ]
            ]);
            $statusCheckTech->save();
            echo "The status with the " . Status::CHECK_TECH . " key was created\n";
        }

        $statusOffer = Status::find()->where(['key' => Status::OFFER])->one();
        if (!$statusOffer) {
            $statusOffer = new Status([
                'key' => Status::OFFER,
                'order' => 40,
                'color' => '#3424dc',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Offer',
                        'uk' => 'Розрахунок КП'
                    ]
                ]
            ]);
            $statusOffer->save();
            echo "The status with the " . Status::OFFER . " key was created\n";
        }

        $statusOfferFormed = Status::find()->where(['key' => Status::OFFER_FORMED])->one();
        if (!$statusOfferFormed) {
            $statusOfferFormed = new Status([
                'key' => Status::OFFER_FORMED,
                'order' => 50,
                'color' => '#125405',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Offer formed',
                        'uk' => 'КП сформовано'
                    ]
                ]
            ]);
            $statusOfferFormed->save();
            echo "The status with the " . Status::OFFER_FORMED . " key was created\n";
        }

        $statusСancel = Status::find()->where(['key' => Status::CANCEL])->one();
        if (!$statusСancel) {
            $statusСancel = new Status([
                'key' => Status::CANCEL,
                'order' => 60,
                'color' => '#e92313',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Cancel',
                        'uk' => 'Відмова'
                    ]
                ]
            ]);
            $statusСancel->save();
            echo "The status with the " . Status::CANCEL . " key was created\n";
        }

        $statusRequestTS = Status::find()->where(['key' => Status::REQUEST_TS])->one();
        if (!$statusRequestTS) {
            $statusRequestTS = new Status([
                'key' => Status::REQUEST_TS,
                'order' => 15,
                'color' => '#f77c66',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Request for engineering of TS',
                        'uk' => 'Заявка на розроблення ТР'
                    ]
                ]
            ]);
            $statusRequestTS->save();
            echo "The status with the " . Status::REQUEST_TS . " key was created\n";
        }

        $statusCorrection = Status::find()->where(['key' => Status::CORRECTION_TT])->one();
        if (!$statusCorrection) {
            $statusCorrection = new Status([
                'key' => Status::CORRECTION_TT,
                'order' => 6,
                'color' => '#f4785b',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Correction',
                        'uk' => 'Коригування ТЗ'
                    ]
                ]
            ]);
            $statusCorrection->save();
            echo "The status with the " . Status::CORRECTION_TT . " key was created\n";
        }

        $statusCorrectionTech = Status::find()->where(['key' => Status::CORRECTION_TECH])->one();
        if (!$statusCorrectionTech) {
            $statusCorrectionTech = new Status([
                'key' => Status::CORRECTION_TECH,
                'order' => 70,
                'color' => '#f7c356',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Technolog correction',
                        'uk' => 'Коригування технології'
                    ]
                ]
            ]);
            $statusCorrectionTech->save();
            echo "The status with the " . Status::CORRECTION_TECH . " key was created\n";
        }

        $statusDiscountRequest = Status::find()->where(['key' => Status::DISCOUNT_REQUEST])->one();
        if (!$statusDiscountRequest) {
            $statusDiscountRequest = new Status([
                'key' => Status::DISCOUNT_REQUEST,
                'order' => 80,
                'color' => '#e58bf2',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Discount request',
                        'uk' => 'Запит знижки'
                    ]
                ]
            ]);
            $statusDiscountRequest->save();
            echo "The status with the " . Status::DISCOUNT_REQUEST . " key was created\n";
        }

        $statusOfferApproval = Status::find()->where(['key' => Status::OFFER_APPROVAL])->one();
        if (!$statusOfferApproval) {
            $statusOfferApproval = new Status([
                'key' => Status::OFFER_APPROVAL,
                'order' => 55,
                'color' => '#383535',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Approval of proposal with customer',
                        'uk' => 'Узгодження КП з клієнтом'
                    ]
                ]
            ]);
            $statusOfferApproval->save();
            echo "The status with the " . Status::OFFER_APPROVAL . " key was created\n";
        }

        $statusDiscountAnsw= Status::find()->where(['key' => Status::DISCOUNT_ANSW])->one();
        if (!$statusDiscountAnsw) {
            $statusDiscountAnsw = new Status([
                'key' => Status::DISCOUNT_ANSW,
                'order' => 90,
                'color' => '#780808',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Discount answer',
                        'uk' => 'Відповідь по знижці'
                    ]
                ]
            ]);
            $statusDiscountAnsw->save();
            echo "The status with the " . Status::DISCOUNT_ANSW . " key was created\n";
        }

        $fileTypeBridgeTray = FileType::find()->where(['key' => ProductOption::BRIDGE_TRAY_FILE])->one();
        if (!$fileTypeBridgeTray) {
            $fileTypeBridgeTray = new FileType([
                'key' => ProductOption::BRIDGE_TRAY_FILE,
                'entity' => 'Order',
                'allowed_extensions' => '.png',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Bridge Tray: File',
                        'uk' => 'Мостовий лоток: Файл'
                    ]
                ]
            ]);
            $fileTypeBridgeTray->save();
            echo "The file with the " . ProductOption::BRIDGE_TRAY_FILE . " key was created\n";
        }

        $fileTypeNonStandardGrilleFile = FileType::find()->where(['key' => ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE])->one();
        if (!$fileTypeNonStandardGrilleFile) {
            $fileTypeNonStandardGrilleFile = new FileType([
                'key' => ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE,
                'entity' => 'Order',
                'allowed_extensions' => '.png',
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Hydraulic Split: Non Standard Grille Type',
                        'uk' => 'Гідравліччне січення: Нестандартна решітка'
                    ]
                ]
            ]);
            $fileTypeNonStandardGrilleFile->save();
            echo "The file with the " . ProductOption::HYDRAULIC_GRILLE_NON_STANDARD_FILE . " key was created\n";
        }

        $printMarkOrderUuid = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_ORDER_UUID])->one();
        if (!$printMarkOrderUuid) {
            $printMarkOrderUuid = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_ORDER_UUID,
                'expression' => '{order.uuid}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Order #',
                        'uk' => 'Номер заявки'
                    ]
                ]
            ]);
            $printMarkOrderUuid->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_ORDER_UUID . " key was created\n";
        }

        $printMarkClientName = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_CLIENT_NAME])->one();
        if (!$printMarkClientName) {
            $printMarkClientName = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_CLIENT_NAME,
                'expression' => '{client.full_name}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Client: Name',
                        'uk' => 'Клієнт: ПІБ'
                    ]
                ]
            ]);
            $printMarkClientName->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_CLIENT_NAME . " key was created\n";
        }

        $printMarkClientPhone = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_CLIENT_PHONE])->one();
        if (!$printMarkClientPhone) {
            $printMarkClientPhone = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_CLIENT_PHONE,
                'expression' => '{client.phone}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Client: Phone',
                        'uk' => 'Клієнт: Телефон'
                    ]
                ]
            ]);
            $printMarkClientPhone->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_CLIENT_PHONE . " key was created\n";
        }

        $printMarkClientAddressLegal = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_CLIENT_ADDRESS_LEGAL])->one();
        if (!$printMarkClientAddressLegal) {
            $printMarkClientAddressLegal = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_CLIENT_ADDRESS_LEGAL,
                'expression' => '{client.address_legal}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Client: Legal Address',
                        'uk' => 'Клієнт: Юридична адреса'
                    ]
                ]
            ]);
            $printMarkClientAddressLegal->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_CLIENT_ADDRESS_LEGAL . " key was created\n";
        }

        $printMarkClientAddressActual = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_CLIENT_ADDRESS_ACTUAL])->one();
        if (!$printMarkClientAddressActual) {
            $printMarkClientAddressActual = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_CLIENT_ADDRESS_ACTUAL,
                'expression' => '{client.address_actual}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Client: Actual Address',
                        'uk' => 'Клієнт: Фактична адреса'
                    ]
                ]
            ]);
            $printMarkClientAddressActual->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_CLIENT_ADDRESS_ACTUAL . " key was created\n";
        }

        $printMarkUserName = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_USER_NAME])->one();
        if (!$printMarkUserName) {
            $printMarkUserName = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_USER_NAME,
                'expression' => '{user.full_name}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Order Creator Name',
                        'uk' => 'Створювач заявки: ПІБ'
                    ]
                ]
            ]);
            $printMarkUserName->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_USER_NAME . " key was created\n";
        }

        $printMarkProductTitle = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_PRODUCT_TITLE])->one();
        if (!$printMarkProductTitle) {
            $printMarkProductTitle = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_PRODUCT_TITLE,
                'expression' => '{product.title}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Product Name',
                        'uk' => 'Назва продукту'
                    ]
                ]
            ]);
            $printMarkProductTitle->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_PRODUCT_TITLE . " key was created\n";
        }

        $printMarkHydraulicSplit = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_SPLIT])->one();
        if (!$printMarkHydraulicSplit) {
            $printMarkHydraulicSplit = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_SPLIT,
                'expression' => '{option.hydraulic_split}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Hydraulic Split',
                        'uk' => 'Система'
                    ]
                ]
            ]);
            $printMarkHydraulicSplit->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_SPLIT . " key was created\n";
        }

        $printMarkHeightMin = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HEIGHT_MIN])->one();
        if (!$printMarkHeightMin) {
            $printMarkHeightMin = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HEIGHT_MIN,
                'expression' => '{option.height_min}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: H min',
                        'uk' => 'Внутрішній водовідвід: H min '
                    ]
                ]
            ]);
            $printMarkHeightMin->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HEIGHT_MIN . " key was created\n";
        }

        $printMarkHeightMax = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HEIGHT_MAX])->one();
        if (!$printMarkHeightMax) {
            $printMarkHeightMax = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HEIGHT_MAX,
                'expression' => '{option.height_max}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: H max',
                        'uk' => 'Внутрішній водовідвід: H max '
                    ]
                ]
            ]);
            $printMarkHeightMax->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HEIGHT_MAX . " key was created\n";
        }

        $printMarkTrayLength = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_TRAY_LENGTH])->one();
        if (!$printMarkTrayLength) {
            $printMarkTrayLength = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_TRAY_LENGTH,
                'expression' => '{option.hydraulic_tray_length}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Tray Length',
                        'uk' => 'Внутрішній водовідвід: Довжина лотка'
                    ]
                ]
            ]);
            $printMarkTrayLength->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_TRAY_LENGTH . " key was created\n";
        }

        $printMarkTraySlope = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_TRAY_SLOPE])->one();
        if (!$printMarkTraySlope) {
            $printMarkTraySlope = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_TRAY_SLOPE,
                'expression' => '{option.hydraulic_tray_slope}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Tray Slope',
                        'uk' => 'Внутрішній водовідвід: Ухил лотка'
                    ]
                ]
            ]);
            $printMarkTraySlope->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_TRAY_SLOPE . " key was created\n";
        }

        $printMarkTraySlope = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_CONNECTION_TYPE])->one();
        if (!$printMarkTraySlope) {
            $printMarkTraySlope = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_CONNECTION_TYPE,
                'expression' => '{option.hydraulic_connection_type}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Type Of Connection',
                        'uk' => "Внутрішній водовідвід: Тип з'єднання"
                    ]
                ]
            ]);
            $printMarkTraySlope->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_CONNECTION_TYPE . " key was created\n";
        }

        $printMarkDrainageType = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_DRAINAGE_TYPE])->one();
        if (!$printMarkDrainageType) {
            $printMarkDrainageType = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_DRAINAGE_TYPE,
                'expression' => '{option.hydraulic_drainage_type}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Type Of Drainage',
                        'uk' => "Внутрішній водовідвід: Тип водовідведення"
                    ]
                ]
            ]);
            $printMarkDrainageType->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_DRAINAGE_TYPE . " key was created\n";
        }

        $printMarkReleaseDirection = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_RELEASE_DIRECTION])->one();
        if (!$printMarkReleaseDirection) {
            $printMarkReleaseDirection = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_RELEASE_DIRECTION,
                'expression' => '{option.hydraulic_release_direction}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Release Direction',
                        'uk' => 'Внутрішній водовідвід: Напрямок випуску'
                    ]
                ]
            ]);
            $printMarkReleaseDirection->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_RELEASE_DIRECTION . " key was created\n";
        }

        $printMarkReleasePlacement = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_RELEASE_PLACEMENT])->one();
        if (!$printMarkReleasePlacement) {
            $printMarkReleasePlacement = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_RELEASE_PLACEMENT,
                'expression' => '{option.hydraulic_release_placement}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Release Placement',
                        'uk' => 'Внутрішній водовідвід: Pозташування випуску'
                    ]
                ]
            ]);
            $printMarkReleasePlacement->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_RELEASE_PLACEMENT . " key was created\n";
        }

        $printMarkGrilleType = PrintedFormFormula::find()->where(['key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_TYPE])->one();
        if (!$printMarkGrilleType) {
            $printMarkGrilleType = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_TYPE,
                'expression' => '{option.hydraulic_grille_type}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Internal Drainage: Grille Type',
                        'uk' => 'Внутрішній водовідвід: Тип решітки'
                    ]
                ]
            ]);
            $printMarkGrilleType->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_GRILLE_TYPE . " key was created\n";
        }

        $hydraulicGrilleAdjustmentByCustomerMark = PrintedFormFormula::find()->where([
            'key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_CUSTOMER
        ])->one();
        if (!$hydraulicGrilleAdjustmentByCustomerMark) {
            $hydraulicGrilleAdjustmentByCustomerMark = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_CUSTOMER,
                'expression' => '{option.hydraulic_grille_adjustment_by_customer}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Hydraulic Grille Adjustment By Customer',
                        'uk' => 'Підрізка решіток замовником'
                    ]
                ]
            ]);
            $hydraulicGrilleAdjustmentByCustomerMark->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_CUSTOMER . " key was created\n";
        }

        $hydraulicGrilleAdjustmentByManufacturerMark = PrintedFormFormula::find()->where([
            'key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_MANUFACTURER
        ])->one();
        if (!$hydraulicGrilleAdjustmentByManufacturerMark) {
            $hydraulicGrilleAdjustmentByManufacturerMark = new PrintedFormFormula([
                'key' => PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_MANUFACTURER,
                'expression' => '{option.hydraulic_grille_adjustment_by_manufacturer}',
                'is_system' => 1,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Hydraulic Grille Adjustment By Manufacturer',
                        'uk' => 'Підрізка решіток на виробництві'
                    ]
                ]
            ]);
            $hydraulicGrilleAdjustmentByManufacturerMark->save();
            echo "The printed form formula with the " . PrintedFormFormula::KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_MANUFACTURER . " key was created\n";
        }

        $instructionLengthAndHeight = InstructionSourceMessage::find()->where([
            'message' => 'Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max'
        ])->one();
        if (!$instructionLengthAndHeight) {
            $instructionLengthAndHeight = new InstructionSourceMessage([
                'category' => InstructionSourceMessage::CATEGORY_DEFAULT,
                'message' => 'Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max'
            ]);
            $instructionLengthAndHeight->save();
            $instructionLengthAndHeight->addTranslations([
                'uk' => "Обов'язково вказати довжину лотка та заповнити на вибір одне з трьох полів:<br>ухил, Hmin, Hmax"
            ], '\app\models\InstructionMessage');
            echo "The instruction 'Length and height' was created\n";
        }

        $instructionOverallLineLength = InstructionSourceMessage::find()->where([
            'message' => 'Overall line length = tray length + ladder gauge (if selected) + end lid gauge'
        ])->one();
        if (!$instructionOverallLineLength) {
            $instructionOverallLineLength = new InstructionSourceMessage([
                'category' => InstructionSourceMessage::CATEGORY_DEFAULT,
                'message' => 'Overall line length = tray length + ladder gauge (if selected) + end lid gauge'
            ]);
            $instructionOverallLineLength->save();
            $instructionOverallLineLength->addTranslations([
                'uk' => "Габаритна довжина лінії = довжина лотка + габарит трапа (якщо вибрано) + габарит торцевих кришок"
            ], '\app\models\InstructionMessage');
            echo "The instruction 'Overall line length' was created\n";
        }

        $this->hidraulicSplitEuroVariants();
        $this->statusLogCommentReasons();
        $this->fileTypes();
        $this->setSystemOptions();
    }

    public function setSystemOptions() 
    {
        $optionKeys = [
            ProductOption::HYDRAULIC_SPLIT,
            ProductOption::HYDRAULIC_RELEASE_PLACEMENT,
            ProductOption::HYDRAULIC_TRAY_LENGTH,
            ProductOption::HYDRAULIC_TRAY_SLOPE,
            ProductOption::HEIGHT_MIN,
            ProductOption::HEIGHT_MAX,
            ProductOption::HYDRAULIC_CONNECTION_TYPE,
            ProductOption::HYDRAULIC_DRAINAGE_TYPE,
            ProductOption::HYDRAULIC_RELEASE_DIRECTION,
            ProductOption::OUTFALL_DIAMETER,
            ProductOption::HYDRAULIC_WATER_SEAL,
            ProductOption::HYDRAULIC_WATER_SEAL_AND_CATCHER,
            ProductOption::NO_END_LID_IN_BEGINNING_OF_LINE,
            ProductOption::HYDRAULIC_SPLIT,
        ];

        foreach ($optionKeys as $optionKey) {
            $option = ProductOption::find()->where(['option_key' => $optionKey])->one();
            if ($option) {
                $option->updateAttributes(['is_system' => 1]);
            }

            echo "The option with the key of $optionKey was set as system \n";
        }
    }

    public function fileTypes() {
        $printedTemplateImageFileType = FileType::find()
        ->where([
            'key' => FileType::PRINTED_TEMPLATE_IMAGE
        ])->one();

        if (!$printedTemplateImageFileType) {
            $fileTypePrintedImage = new FileType([
                'key' => FileType::PRINTED_TEMPLATE_IMAGE,
                'entity' => 'Printed template image',
                'allowed_extensions' => implode(', ', FileType::IMAGE_EXTENSIONS),
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Printed template image',
                        'uk' => 'Зображення для друковних форм'
                    ]
                ]
            ]);

            $fileTypePrintedImage->save();

            echo 'The type of file "Printed template image" was created ' . PHP_EOL;
        }
    }

    public function hidraulicSplitEuroVariants()
    {
        $euroConfig = [
            'euro_100' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 100',
                        'uk' => 'Євро 100'
                    ]
                ],
                'heightMin' => '65',
                'heightMax' => '100'
            ],
            'euro_200' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 200',
                        'uk' => 'Євро 200'
                    ]
                ],
                'heightMin' => '75',
                'heightMax' => '20'
            ],
            'euro_120' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 120',
                        'uk' => 'Євро 120'
                    ]
                ],
                'heightMin' => '65',
                'heightMax' => '120'
            ],
            'euro_150' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 150',
                        'uk' => 'Євро 150'
                    ]
                ],
                'heightMin' => '70',
                'heightMax' => '150'
            ],
            'euro_250' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 250',
                        'uk' => 'Євро 250'
                    ]
                ],
                'heightMin' => '80',
                'heightMax' => '250'
            ],
            'euro_300' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 300',
                        'uk' => 'Євро 300'
                    ]
                ],
                'heightMin' => '85',
                'heightMax' => '300'
            ],
            'euro_400' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 400',
                        'uk' => 'Євро 400'
                    ]
                ],
                'heightMin' => '90',
                'heightMax' => '400'
            ],
            'euro_500' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 500',
                        'uk' => 'Євро 500'
                    ]
                ],
                'heightMin' => '100',
                'heightMax' => '500'
            ],
            'euro_600' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 600',
                        'uk' => 'Євро 600'
                    ]
                ],
                'heightMin' => '110',
                'heightMax' => '600'
            ],
            'euro_080' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 080',
                        'uk' => 'Євро 080'
                    ]
                ],
                'heightMin' => '45',
                'heightMax' => '80'
            ],
            'euro_080_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 080 SP',
                        'uk' => 'Євро 080 СП'
                    ]
                ],
                'heightMin' => '80',
                'heightMax' => '150'
            ],
            'euro_100_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 100 SP',
                        'uk' => 'Євро 100 СП'
                    ]
                ],
                'heightMin' => '80',
                'heightMax' => '300'
            ],
            'euro_120_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 120 SP',
                        'uk' => 'Євро 120 СП'
                    ]
                ],
                'heightMin' => '80',
                'heightMax' => '300'
            ],
            'euro_150_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 150 SP',
                        'uk' => 'Євро 150 СП'
                    ]
                ],
                'heightMin' => '90',
                'heightMax' => '400'
            ],
            'euro_200_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 200 SP',
                        'uk' => 'Євро 200 СП'
                    ]
                ],
                'heightMin' => '100',
                'heightMax' => '500'
            ],
            'euro_250_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 250 SP',
                        'uk' => 'Євро 250 СП'
                    ]
                ],
                'heightMin' => '110',
                'heightMax' => '500'
            ],
            'euro_300_sp' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Euro 300 SP',
                        'uk' => 'Євро 300 СП'
                    ]
                ],
                'heightMin' => '125',
                'heightMax' => '500'
            ],
            'mini_100' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Mini 100',
                        'uk' => 'Міні 100'
                    ]
                ],
                'heightMin' => '35',
                'heightMax' => '100'
            ],
            'mini_200' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Mini 200',
                        'uk' => 'Міні 200'
                    ]
                ],
                'heightMin' => '40',
                'heightMax' => '200'
            ],
            'slit' => [
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Slit',
                        'uk' => 'Щілинна'
                    ]
                ],
                'heightMin' => '70',
                'heightMax' => '200'
            ],
        ];

        $internalDrainage = Product::find()->where(['product_key' => Product::INTERNAL_DRAINAGE])->one();
        $hydraulicSplit = ProductOption::find()->where(['option_key' => ProductOption::HYDRAULIC_SPLIT])->one();

        foreach ($euroConfig as $euroKey => $params) {
            $euro = ProductOption::find()->where(['option_key' => $euroKey])->one();
            if (!$euro) {
                $euro = new ProductOption([
                    'option_key' => $euroKey,
                    'product_id' => $internalDrainage->id,
                    'parent_id' => $hydraulicSplit->id,
                    'option_type' => 4,
                    'translations' => $params['translations']
                ]);
                $euro->save();
                echo "The product option with the " . $euroKey . " key was created\n";
            }

            $heightMinKey = $euroKey . '_height_min';
            $euroHeightMin = ProductOption::find()->where(['option_key' => $heightMinKey])->one();
            if (!$euroHeightMin) {
                $euroHeightMin = new ProductOption([
                    'option_key' => $heightMinKey,
                    'product_id' => $internalDrainage->id,
                    'parent_id' => $euro->id,
                    'option_type' => 1,
                    'value' => $params['heightMin'],
                    'measurement_unit' => 'mm',
                    'translations' => [
                        'title_source_message_id' => [
                            'en-US' => 'H min',
                            'uk' => 'H min'
                        ]
                    ]
                ]);
                $euroHeightMin->save();
                echo "The product option with the " . $heightMinKey . " key was created\n";
            }
    
            $heightMaxKey = $euroKey . '_height_max';
            $euroHeightMax = ProductOption::find()->where(['option_key' => $heightMaxKey])->one();
            if (!$euroHeightMax) {
                $euroHeightMax = new ProductOption([
                    'option_key' => $heightMaxKey,
                    'product_id' => $internalDrainage->id,
                    'parent_id' => $euro->id,
                    'option_type' => 1,
                    'value' => $params['heightMax'],
                    'measurement_unit' => 'mm',
                    'translations' => [
                        'title_source_message_id' => [
                            'en-US' => 'H max',
                            'uk' => 'H max'
                        ]
                    ]
                ]);
                $euroHeightMax->save();
                echo "The product option with the " . $heightMaxKey . " key was created\n";
            }
        } 
    }

    public function statusLogCommentReasons()
    {
        $statusCancel = Status::find()->where(['key' => Status::CANCEL])->one();
        $statusCanceId = $statusCancel->id;
        $reasons = [
            [
                'status_id' => $statusCanceId,
                'reason_key' => StatusCommentReason::NOT_POSSIBLE_TECH,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Not possible to do technologically',
                        'uk' => 'Неможливо зробити технологічно'
                    ]
                ],
            ],
            [
                'status_id' => $statusCanceId,
                'reason_key' => StatusCommentReason::NOT_POSSIBLE_CONSTRUCTIVE,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Not possible to do without making a change to constructive',
                        'uk' => 'Неможливо зробити без зміни конструктиву'
                    ]
                ],
            ],
            [
                'status_id' => $statusCanceId,
                'reason_key' => StatusCommentReason::R_D_TASK,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'R&D task (create a task on the Portal, according to Regulation)',
                        'uk' => 'Завдання R & D (поставити на Порталі згідно з Регламентом)'
                    ]
                ],
            ],
            [
                'status_id' => $statusCanceId,
                'reason_key' => StatusCommentReason::DOES_NOT_COMPLY_MISSION,
                'translations' => [
                    'title_source_message_id' => [
                        'en-US' => 'Does not comply with Standartpark’s mission',
                        'uk' => 'Не відповідає місії Стандартпарк'
                    ]
                ],
            ],
        ];

        foreach ($reasons as $reasonOptions) {
            $reason = StatusCommentReason::find()->where(['reason_key' => $reasonOptions['reason_key']])->one();
            if (!$reason) {
                $reason = new StatusCommentReason([
                    'status_id' => $reasonOptions['status_id'],
                    'reason_key' => $reasonOptions['reason_key'],
                    'translations' => $reasonOptions['translations']
                ]);
                $reason->save();
                echo "The status's comment reason with the " . $reasonOptions['reason_key'] . " key was created\n";
            }
        }
    }
}
