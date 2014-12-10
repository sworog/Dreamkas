package ru.dreamkas.pos.espresso.testSuites;

import com.squareup.spoon.Spoon;

import java.util.ArrayList;

import ru.dreamkas.pos.espresso.steps.KasSteps;
import ru.dreamkas.pos.espresso.steps.KasThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.activities.WelcomeActivity_;

public class Us113_4 extends BaseTestSuite<WelcomeActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_4()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", WelcomeActivity_.class);
    }

    //Scenario: Проверка формирования чека
    public void testFillReceipt() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.clickOnProductInSearchResult("Вар2");
        Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkReceipt(2, ethalon);
        Spoon.screenshot(getCurrentActivity(), "info");
        KasThen.checkReceiptTotal("Продать на сумму 380,00 ₽");
        Spoon.screenshot(getCurrentActivity(), "info");
    }


}
