<?php

class ReadmeTest extends Archetype\Tests\FileTestCase
{
    /**
     * Example from: https://github.com/ajthinking/archetype#laravelfile-readwrite-api
     *
     * @test
     */
    public function it_can_edit_files_and_produce_valid_ast()
    {
        $file = LaravelFile::user()
            ->add()->use(['App\Traits\Dumpable', 'App\Contracts\PlayerInterface'])
            ->add()->implements('PlayerInterface')
            ->add()->trait('Dumpable')
            ->table('gdpr_users')
            ->add()->fillable('nickname')
            ->remove()->hidden()
            ->empty()->casts()
            //->hasMany('App\Game')
            ->belongsTo('App\Guild');

        // The $file has changed
        $this->assertNotEquals(
            LaravelFile::user()->render(),
            $file->render(),
        );

        // The produced code is valid - it can be reparsed into a new LaravelFile instance
        $recreatedFile = LaravelFile::fromString(
            $file->render()
        );

        // The original and recreated files will render identical code 
        $this->assertEquals(
            $file->render(),
            $recreatedFile->render()
        );

        // The file instances are not using the same references
        // i.e we can change ast of one and expect a diff
        $this->assertNotEquals(
            $file->render(),
            $recreatedFile->className('NewName')->render()
        );
    }

    public function it_wont_wreck_file_formatting()
    {
        // TO BE COMPLETED AFTER RESOLVING THE SNIPPET/TEMPLATING/BELONGS TO ETC ISSUE!

        $code = <<<'CODE'
        <?php

        namespace App\Models;
        
        use App\Contracts\PlayerInterface;
        use App\Traits\Dumpable;
        use Illuminate\Contracts\Auth\MustVerifyEmail;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Foundation\Auth\User as Authenticatable;
        use Illuminate\Notifications\Notifiable;
        
        class User extends Authenticatable implements PlayerInterface
        {
            use HasFactory, Notifiable;
            
            protected $table = 'gdpr_users';
            
            /**
             * The attributes that are mass assignable.
             *
             * @var array
             */
            protected $fillable = ['name', 'email', 'password', 'nickname'];
            
            /**
             * The attributes that should be cast to native types.
             *
             * @var array
             */
            protected $casts = [];
            
            /**
             * Get the associated Guild
             */
            public function guild()
            {
                return $this->belongsTo(Guild::class);
            }
            
            /**
             * Get the associated Games
             */
            public function games()
            {
                return $this->hasMany(Game::class);
            }
        }
        CODE;

        
        $this->assertEquals(
            $code,
            $file->render()
        );        
    }
}
