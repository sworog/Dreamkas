package ru.dreamkas.pos.espresso.testSuites;

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

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");

        KasThen.checkEditReceiptItemModal("0,00 ₽", "Товар без цены продажи", "", "1,0");
    }

    //Scenario: Scenario: Проверка добавления товара без цены после редактирования
    public void testAddEditedProductToReceipt() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");

        onView(withId(R.id.txtSellingPrice)).perform(typeText("100"));

        CommonSteps.clickOnViewWithId(R.id.btnSave);

        KasThen.checkReceipt(1, ethalon);
    }

    //Scenario: Отказ пользователя от редактирования товара без цены при добавлении его в чек
    public void testCancelEditProductWithZeroPrice() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");

        onView(withId(R.id.txtSellingPrice)).perform(typeText("100"));

        CommonSteps.clickOnViewWithId(R.id.btnCloseModal);

        KasThen.checkEmptyReceipt();
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testEditReceiptItemIncrementQuantity() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");

        onView(withId(R.id.txtValue)).perform(new EspressoHelper.SetTextAction("15,351"));

        CommonSteps.clickOnViewWithId(R.id.btnUp);

        onView(withId(R.id.txtValue)).check(matches(withText(("16,351"))));
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testEditReceiptItemDencrementQuantity() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар без цены продажи");

        onView(withId(R.id.txtValue)).perform(new EspressoHelper.SetTextAction("15,351"));

        CommonSteps.clickOnViewWithId(R.id.btnDown);

        onView(withId(R.id.txtValue)).check(matches(withText(("14,351"))));
    }

    //Scenario: Проверка изменения количества товара по нажатию на "+"
    public void testModalEditReceiptItem() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasSteps.clickOnProductInSearchResult("Товар1");

        KasSteps.clickOnProductInReceipt("Товар1");

        KasThen.checkEditReceiptItemModal("250,00 ₽", "Товар1", "250,00", "1,0");
    }

    //Scenario: Проверка текста кнопки подтверждения удаления товарной позиции из чека
    public void testClearReceiptButtonText1() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        KasSteps.clickOnProductInSearchResult("Товар1");

        KasSteps.clickOnProductInReceipt("Товар1");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());

        onView(withId(R.id.btnRemoveFromReceipt)).check(matches(withText("Подтвердить удаление")));
    }

    //Scenario: Проверка отказа от удаления товарной позиции из чека
    public void testCancelRemoveFromReceipt() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        KasSteps.clickOnProductInSearchResult("Товар1");

        KasSteps.clickOnProductInReceipt("Товар1");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());

        waitForView(R.id.btnUp, 3000, isDisplayed());
        onView(withId(R.id.btnUp)).perform(click());

        waitForView(R.id.btnRemoveFromReceipt, 10000, isDisplayed());
        onView(withId(R.id.btnRemoveFromReceipt)).check(matches(withText("УДАЛИТЬ ИЗ ЧЕКА")));
    }

    //Scenario: Проверка удаления позиции из чека
    public void testRemoveFromReceipt() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        KasSteps.search("100");

        KasSteps.clickOnProductInSearchResult("Товар1");

        KasSteps.clickOnProductInReceipt("Товар1");

        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());
        onView(withId(R.id.btnRemoveFromReceipt)).perform(click());

        KasThen.checkEmptyReceipt();
    }

}
