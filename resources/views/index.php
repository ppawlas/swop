<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ocena.IQ</title>
		<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" href="node_modules/angular-ui-select/select.css">
		<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" href="node_modules/angular-block-ui/dist/angular-block-ui.css">
		<link rel="stylesheet" href="assets/css/swop.css">
	</head>
	<body ng-app="authApp">
	    <header>
            <div ui-view="navbar"></div>
        </header>

		<div class="container-fluid">
			<div ui-view="content" class="content"></div>
		</div>

	</body>
	<!-- Application Dependencies -->
	<script src="node_modules/angular/angular.js"></script>
	<script src="node_modules/angular-ui-router/build/angular-ui-router.js"></script>
	<script src="node_modules/satellizer/satellizer.js"></script>
	<script src="node_modules/angular-ui-bootstrap/ui-bootstrap-tpls.js"></script>
	<script src="node_modules/angular-confirm/angular-confirm.js"></script>
	<script src="node_modules/angular-sanitize/angular-sanitize.js"></script>
	<script src="node_modules/angular-ui-select/select.js"></script>
	<script src="node_modules/angular-utils-pagination/dirPagination.js"></script>
	<script src="node_modules/angular-animate/angular-animate.js"></script>
	<script src="node_modules/angular-block-ui/dist/angular-block-ui.js"></script>
	<script src="node_modules/angular-translate/dist/angular-translate.js"></script>
	<script src="node_modules/angular-translate/dist/angular-translate-loader-static-files/angular-translate-loader-static-files.js"></script>
	<script src="node_modules/angular-i18n/angular-locale_pl-pl.js"></script>

	<!-- Application Scripts -->
	<script src="app/app.js"></script>
	<script src="app/components/auth/authController.js"></script>
	<script src="app/components/user/userController.js"></script>
	<script src="app/components/nav/navController.js"></script>
	<script src="app/components/dash/dashController.js"></script>
	<script src="app/components/group/groupController.js"></script>
	<script src="app/components/group/groupEditController.js"></script>
	<script src="app/components/group/groupNewController.js"></script>
	<script src="app/components/indicator/indicatorController.js"></script>
	<script src="app/components/indicator/indicatorManagerController.js"></script>
	<script src="app/components/report/reportController.js"></script>
	<script src="app/components/report/reportEditController.js"></script>
	<script src="app/components/report/reportNewController.js"></script>
	<script src="app/components/report/reportPreviewController.js"></script>
	<script src="app/components/report/reportViewController.js"></script>
	<script src="app/components/organization/organizationController.js"></script>
	<script src="app/components/organization/organizationNewController.js"></script>
	<script src="app/components/organization/organizationEditController.js"></script>
	<script src="app/shared/organization/organizationService.js"></script>
	<script src="app/shared/indicator/indicatorService.js"></script>
	<script src="app/shared/group/groupService.js"></script>
	<script src="app/shared/report/reportService.js"></script>
	<script src="app/shared/helper/messageService.js"></script>
	<script src="app/shared/user/userService.js"></script>
	<script src="app/shared/auth/authService.js"></script>
</html>