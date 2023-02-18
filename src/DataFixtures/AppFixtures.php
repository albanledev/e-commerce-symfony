<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i <= 51; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
            );
            if ($i === 1) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $manager->persist($user);
        }

        $manager->flush();


        for ($i = 1; $i <= 11; $i++) {
            $category = new Category();
            $category->setName("Catégorie n°" . $i);

            $manager->persist($category);

        }
        $manager->flush();


        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        for ($z = 1; $z < 201; $z++) {
            $product = new Product();
            $product->setCategory($categories[rand(0, count($categories) - 1)]);
            $product->setName('Product n°' . $z);
            $product->setPrice(rand(2, 3000) / 100);
            $product->setDescription("lorem ipsum dolor set ames");
            $product->setStock(rand(1, 10));
            $manager->persist($product);


        }
        $manager->flush();

    }
}
