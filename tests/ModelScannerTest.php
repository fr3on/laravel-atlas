<?php

use Fr3on\Atlas\Scanners\ModelScanner;
use Illuminate\Support\Facades\File;

test('it can scan models', function () {
    $modelsPath = app_path('Models');
    if (! File::exists($modelsPath)) {
        File::makeDirectory($modelsPath, 0755, true);
    }

    $modelContent = <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['title', 'content'];
    protected $hidden = ['secret'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
PHP;

    File::put($modelsPath.'/Post.php', $modelContent);
    require_once $modelsPath.'/Post.php';

    // We need to make sure the class is loaded.
    // Since we're in a test and the file is in a temporary path,
    // we might need to manually include it or mock the scanner.
    // However, the scanner uses File::allFiles and getClassFromFile.

    $scanner = new ModelScanner;
    $models = $scanner->scan();

    $post = $models->firstWhere('name', 'Post');

    expect($post)->not->toBeNull();
    expect($post['table'])->toBe('posts');
    expect($post['fillable'])->toContain('title', 'content');
    expect($post['hidden'])->toContain('secret');
    expect($post['relations'])->toHaveCount(1);
    expect($post['relations'][0]['name'])->toBe('comments');
    expect($post['relations'][0]['type'])->toBe('HasMany');

    // Cleanup
    File::delete($modelsPath.'/Post.php');
});
