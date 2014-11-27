package ru.dreamkas.pos.espresso.testSuites;

import com.squareup.spoon.Spoon;

import java.util.ArrayList;

import ru.dreamkas.pos.espresso.steps.KasSteps;
import ru.dreamkas.pos.espresso.steps.KasThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_3 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_3()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Поиск товара по части артикулу
    public void testSearchBySku() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");

        //waitForData(withProduct("Товар1"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
            add(new Product(){{setName("Товар3"); setSku("10003"); setBarcode("33333333");}});
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};
        Spoon.screenshot(getCurrentActivity(), "info");
        KasThen.checkSearchProductResult(4, ethalon);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Поиск товара по названию
    public void testSearchByTitle() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("Товар");

        //waitForData(withProduct("Товар1"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Товар3"); setSku("10003"); setBarcode("33333333");}});
        }};
        Spoon.screenshot(getCurrentActivity(), "info");
        KasThen.checkSearchProductResult(3, ethalon);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Поиск товара по штрих-коду
    public void testSearchByBarcode() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");

        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.search("2222");
        Spoon.screenshot(getCurrentActivity(), "info");

        //waitForData(withProduct("Вар2"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};
        Spoon.screenshot(getCurrentActivity(), "info");
        KasThen.checkSearchProductResult(1, ethalon);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка сообщения автокомплита о том, что надо ввести 3 или более символа
    public void testSearchResultMessageTooShortQuery() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("qw", false);
        Spoon.screenshot(getCurrentActivity(), "info");

        waitForView("Для поиска товара введите 3 или более символа.", 10000);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка сообщения автокомплита о том, что надо ввести 3 или более символа
    public void testSearchResultEmpty() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("Такого товара нет");
        Spoon.screenshot(getCurrentActivity(), "info");

        waitForView("Продуктов не найдено.", 20000);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка представления товара без штрихкода
    public void testSearchResultWithoutBarcode() throws Exception {
       //todo impl
    }
}
