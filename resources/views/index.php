<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>SWOP</title>
		<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" href="node_modules/angular-ui-select/select.css">
	</head>
	<body ng-app="authApp">

		<div class="container">
			<div ui-view></div>
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

	<!-- Application Scripts -->
	<script src="app/app.js"></script>
	<script src="app/components/auth/authController.js"></script>
	<script src="app/components/user/userController.js"></script>
	<script src="app/shared/organization/organizationService.js"></script>
</html>