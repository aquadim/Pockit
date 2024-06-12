<?php
// Страница настроек тем

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class ThemeView extends LayoutView {

    protected $themes;

	public function customScripts() { ?>
<script src='/js/themesView.js'></script>

<script>
<?php
$all_themes = [];
while ($theme = $this->themes->fetchArray()) {
    $all_themes[] = [
        'id' => $theme['id'],
        'name' => $theme['name'],
        'author' => $theme['author']
    ];
}
?>
const themes = <?= json_encode($all_themes) ?>;
for (const theme of themes) {
    const createdTheme = getTheme(theme);
    lstThemes.append(createdTheme);
}
</script>
	
	<?php } public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Темы</h1>
    <div id='lstThemes'></div>
	<button id='btnAddTheme' class='btn success w-100'>Добавить тему</button>
</div>

<?php }
}
