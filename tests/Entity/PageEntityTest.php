<?php

declare(strict_types=1);

namespace BinSoul\Test\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageTranslationEntity;
use PHPUnit\Framework\TestCase;

class PageEntityTest extends TestCase
{
    public function test_synchronizes_translations(): void
    {
        $page1 = new PageEntity();

        $translation1 = new PageTranslationEntity();
        $translation1->setPage($page1);

        $this->assertCount(1, $page1->getTranslations());

        $translation2 = new PageTranslationEntity();
        $translation2->setPage($page1);

        $this->assertCount(2, $page1->getTranslations());

        $page1->removeTranslation($translation1);
        $this->assertCount(1, $page1->getTranslations());

        $page1->addTranslation($translation1);
        $this->assertCount(2, $page1->getTranslations());

        $page2 = new PageEntity();
        $translation1->setPage($page2);

        $this->assertCount(1, $page1->getTranslations());
        $this->assertCount(1, $page2->getTranslations());
    }
}
