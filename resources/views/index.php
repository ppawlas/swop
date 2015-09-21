<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>SWOP</title>
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

		<div class="container">
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

	<!-- Application Scripts -->
	<script src="app/app.js"></script>
	<script src="app/components/auth/authController.js"></script>
	<script src="app/components/user/userController.js"></script>
	<script src="app/components/nav/navController.js"></script>
	<script src="app/components/dash/dashController.js"></script>
	<script src="app/components/group/groupController.js"></script>
	<script src="app/components/indicator/indicatorController.js"></script>
	<script src="app/components/report/reportController.js"></script>
	<script src="app/components/organization/organizationController.js"></script>
	<script src="app/components/organization/organizationNewController.js"></script>
	<script src="app/components/organization/organizationEditController.js"></script>
	<script src="app/shared/organization/organizationService.js"></script>
	<script src="app/shared/user/userService.js"></script>
	<script src="app/shared/auth/authService.js"></script>
</html>