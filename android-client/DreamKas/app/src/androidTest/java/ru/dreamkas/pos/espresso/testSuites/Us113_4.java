package ru.dreamkas.pos.espresso.testSuites;

import java.util.ArrayList;

import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.espresso.steps.KasSteps;
import ru.dreamkas.pos.espresso.steps.KasThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_4 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_4()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Проверка формирования чека
    public void testFillReceipt() throws Exception {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");
        KasSteps.clickOnProductInSearchResult("Вар2");

        KasThen.checkReceipt(2, ethalon);
        KasThen.checkReceiptTotal("Продать на сумму 380,00 ₽");
    }


}
