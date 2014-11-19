package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;

import org.jbehave.core.model.ExamplesTable;

import java.util.List;
import java.util.Map;

import static org.hamcrest.Matchers.hasItem;
import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class PosSteps extends GeneralSteps {

    public PosSteps(Pages pages) {
        super(pages);
        setCurrentPageObject("касса");
    }

    @Step
    public void assertActionBarTitle(String expectedTitle) {
        assertText("Заголовок окна", expectedTitle);
    }

    @Step
    public void chooseSpinnerItemWithValue(String value) {
        setValue("Магазины", value);
    }

    @Step
    public void assertStore(String store) {
        assertText("Магазин", store);
    }

    @Step
    public void openDrawerAndClickOnDrawerOption(String menuOption) {
        setValue("Боковое меню", menuOption);
    }

    @Step
    public void inputProductSearchQuery(String productSearchQuery) {
        setValue("Поиск товаров", productSearchQuery);
    }

    @Step
    public void assertSearchProductsResult(Integer count) {
        assertThat(getItems("Результаты поиска товаров").size(), is(count));
    }

    @Step
    public void assertSearchProductsResult(String containsProductTitle) {
        assertThat(getItems("Результаты поиска товаров"), hasItem(containsProductTitle));
    }

    @Step
    public void assertSearchResultEmptyLabelText(String expected) {
        assertText("Сообщение о результатах поиска товаров", expected);
    }

    @Step
    public void assertReceiptEmptyLabelText(String expected) {
        assertText("Сообщение пустого чека", expected);
    }

    public void tapOnProductInSearchResultWithTitle(String title) {
        clickOnListItem("Результаты поиска товаров", title);
    }

    public void tapOnProductInReceiptWithTitle(String title) {
        clickOnListItem("Чек продажи", title);
    }

    public void assertReceiptItemsCount(Integer count) {
        assertThat(getItems("Чек продажи").size(), is(count));
    }

    public void assertReceiptContainsProducts(ExamplesTable examplesTable) {
        List<List<String>> rows = getItems("Чек продажи");
        assertThat(rows.size(), is(examplesTable.getRowCount()));

        for (int i = 0; i < examplesTable.getRowCount(); i++) {
            assertThat(rows.get(i).size(), is(3));

            Map<String, String> ethalonRow = examplesTable.getRow(i);
            assertThat(rows.get(i).get(0), is(ethalonRow.get("Товар")));
            assertThat(rows.get(i).get(1), is(ethalonRow.get("Количество")));
            assertThat(rows.get(i).get(2), is(ethalonRow.get("Стоимость")));
        }
    }

    @Step
    public void assertReceiptTotalButtonText(String expected) {
        assertText("Продать", expected);
    }

    public void clickOnButton(String name) {
        clickOnElement(name);
    }

    public void assertEditReceiptTitle(String expected) {
        assertText("Итог", expected);
    }

    public void assertEditReceiptProductName(String expected) {
        assertText("Наименование", expected);
    }

    public void asserButtonEnabled(String buttonName, Boolean expected) {
        assertThat(getButtonIsEnabled(buttonName), is(expected));
    }



    public void assertEditReceiptSellingPrice(String expected) {
        assertText("Цена продажи", expected);
    }

    public void assertEditReceiptQuantity(String expected) {
        assertText("Количество", expected);
    }
}
