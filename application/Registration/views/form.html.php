<html>
	<title>Web application - registration</title>
	<body>
		<header>
			<h1>Registration</h1>
		</header>
		<form method="post">
			<div>
			<?php if (isset($messages['name'])):?>
				<p>
					<b><?=$messages['name']?></b>
				</p>
			<?php endif;?>
				<input type="text" name="user[name]" placeholder="Enter your name"
				value="<?= isset($data['name']) ? $data['name'] : '' ?>" />
			</div>

			<div>
			<?php if (isset($messages['email'])):?>
				<p><b><?=$messages['email']?></b></p>
			<?php endif;?>
			<input type="text" name="user[email]" placeholder="Enter your email"
			value="<?= isset($data['email']) ? $data['email'] : '' ?>" />
			</div>

			<div>
			<?php if (isset($messages['password'])):?>
				<p><b><?=$messages['password']?></b></p>
			<?php endif;?>
			<input type="password" name="user[password]" placeholder="Enter your password"
			value="<?= isset($data['password']) ? $data['password'] : '' ?>" />
			</div>
    		<?php printf('<input type="hidden" name="_token" value="%s">',
                 htmlspecialchars($signature)); ?>
			<div>
				<br />
				<input type="submit" value="Register now!" />
			</div>
		</form>
	</body>
</html>
