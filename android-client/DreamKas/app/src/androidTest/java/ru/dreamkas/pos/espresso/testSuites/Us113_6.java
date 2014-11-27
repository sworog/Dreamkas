package ru.dreamkas.pos.espresso.testSuites;

import com.squareup.spoon.Spoon;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.EspressoHelper;
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
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_6 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_6()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Проверка модального окна редактирования товарной позиции
    public void testClearReceipt() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");
        Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{

            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");
        Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkEditReceiptItemModal("0,00 ₽", "Товар без цены продажи", "", "1,0");
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Scenario: Проверка добавления товара без цены после редактирования
    public void testAddEditedProductToReceipt() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");
        Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");
        Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.txtSellingPrice)).perform(typeText("100"));
        Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnSave);
        Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkReceipt(1, ethalon);
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Отказ пользователя от редактирования товара без цены при добавлении его в чек
    public void testCancelEditProductWithZeroPrice() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");
        Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");
        Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.txtSellingPrice)).perform(typeText("100"));
        Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnCloseModal);
        Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkEmptyReceipt();
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testEditReceiptItemIncrementQuantity() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");
        Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");
        Spoon.screenshot(getCurrentActivity(), "info");
        onView(withId(R.id.txtValue)).perform(new EspressoHelper.SetTextAction("15,351"));
        Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnUp);
        Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.txtValue)).check(matches(withText(("16,351"))));
        Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testEditReceiptItemDencrementQuantity() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.txtValue)).perform(new EspressoHelper.SetTextAction("15,351"));Spoon.screenshot(getCurrentActivity(), "info");

        CommonSteps.clickOnViewWithId(R.id.btnDown);Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.txtValue)).check(matches(withText(("14,351"))));Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testModalEditReceiptItem() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInReceipt("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkEditReceiptItemModal("250,00 ₽", "Товар1", "250,00", "1,0");Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка текста кнопки подтверждения удаления товарной позиции из чека
    public void testClearReceiptButtonText1() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInReceipt("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnRemoveFromReceipt)).check(matches(withText("Подтвердить удаление")));Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка отказа от удаления товарной позиции из чека
    public void testCancelRemoveFromReceipt() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInReceipt("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());Spoon.screenshot(getCurrentActivity(), "info");

        waitForView(R.id.btnUp, 3000, isDisplayed());Spoon.screenshot(getCurrentActivity(), "info");
        onView(withId(R.id.btnUp)).perform(click());Spoon.screenshot(getCurrentActivity(), "info");

        waitForView(R.id.btnRemoveFromReceipt, 10000, isDisplayed());Spoon.screenshot(getCurrentActivity(), "info");
        onView(withId(R.id.btnRemoveFromReceipt)).check(matches(withText("УДАЛИТЬ ИЗ ЧЕКА")));Spoon.screenshot(getCurrentActivity(), "info");
    }

    //Scenario: Проверка удаления позиции из чека
    public void testRemoveFromReceipt() throws Throwable {
        Spoon.screenshot(getCurrentActivity(), "info");
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");Spoon.screenshot(getCurrentActivity(), "info");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();Spoon.screenshot(getCurrentActivity(), "info");
        KasSteps.search("100");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInSearchResult("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        KasSteps.clickOnProductInReceipt("Товар1");Spoon.screenshot(getCurrentActivity(), "info");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());Spoon.screenshot(getCurrentActivity(), "info");
        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());Spoon.screenshot(getCurrentActivity(), "info");

        KasThen.checkEmptyReceipt();Spoon.screenshot(getCurrentActivity(), "info");
    }

}
