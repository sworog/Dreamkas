package ru.dreamkas.pos.espresso.testSuites;

import com.google.android.apps.common.testing.ui.espresso.ViewInteraction;
import com.squareup.spoon.Spoon;

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

        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.clickOnProductInSearchResult("Вар2");Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);Spoon.screenshot(getCurrentActivity(), "info");
        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkEmptyReceipt();Spoon.screenshot(getCurrentActivity(), "info");
    }


    //Scenario: Проверка текста кнопки подтверждения очистки чека
    public void testClearReceiptButtonText1() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.clickOnProductInSearchResult("Вар2");Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnReceiptClear)).check(matches(withText("Подтвердить очистку чека")));Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка отказа от подтверждения очистки чека
    public void testClearReceiptButtonCancelSubmit() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");
        Spoon.screenshot(getCurrentActivity(), "info");
        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnReceiptClear);Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInSearchResult("Вар2");Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnReceiptClear)).check(matches(withText("Очистить чек")));
        Spoon.screenshot(getCurrentActivity(), "info");
    }


}
