package ru.dreamkas.pos.espresso.testSuites;

import com.google.android.apps.common.testing.ui.espresso.ViewInteraction;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.espresso.steps.CommonSteps;
import ru.dreamkas.pos.espresso.steps.KasSteps;
import ru.dreamkas.pos.espresso.steps.KasThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;

public class Us113_7 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_7()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Проверка формирования чека
    public void testClearReceipt() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");
        KasSteps.clickOnProductInSearchResult("Вар2");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);
        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);

        KasThen.checkEmptyReceipt();
    }


    //Scenario: Проверка текста кнопки подтверждения очистки чека
    public void testClearReceiptButtonText1() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");
        KasSteps.clickOnProductInSearchResult("Вар2");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);

        onView(withId(R.id.btnReceiptClear)).check(matches(withText("Подтвердить очистку чека")));
    }

    //Scenario: Проверка отказа от подтверждения очистки чека
    public void testClearReceiptButtonCancelSubmit() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);

        KasSteps.clickOnProductInSearchResult("Вар2");

        onView(withId(R.id.btnReceiptClear)).check(matches(withText("Очистить чек")));
    }


}
