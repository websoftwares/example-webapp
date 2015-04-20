<html>
	<title>Web application - login</title>
	<body>
		<header>
			<h1>Login</h1>
		</header>
		<form method="post">

			<div>
			<input type="text" name="user[email]" placeholder="Enter your email"
			value="<?= isset($data['email']) ? $data['email'] : '' ?>" />
			</div>

			<div>
			<input type="password" name="user[password]" placeholder="Enter your password"
			value="<?= isset($data['password']) ? $data['password'] : '' ?>" />
			</div>

    		<?php printf('<input type="hidden" name="_token" value="%s">',
                 htmlspecialchars($signature)); ?>


			<?php if (isset($captcha)):?>
				<div>
					<img src="<?= $captcha->inline(); ?>" />
				</div>

				<div>
					<input type="text" name="captcha[phrase]" placeholder="Enter above phrase" value="" />
				</div>

			<?php endif;?>

			<div>
				<br />
				<input type="submit" value="Login" />
			</div>


		</form>

		<br />
		<section>
			<nav>
				<a href="/registration">No account? register!</a>
			</nav>
		</section>
	</body>
</html>
