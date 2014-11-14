package ru.dreamkas.jbehave;

import org.jbehave.core.annotations.Alias;
import ru.dreamkas.steps.PosSteps;
import net.thucydides.core.annotations.Steps;

import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @When("пользователь выбирает магазин с именем '$storeName' из списка")
    @Given("пользователь выбирает магазин с именем '$storeName' из списка")
    public void whenTheUserChooseStoreWithName(String storeName) {
        posSteps.chooseSpinnerItemWithValue(storeName);
    }

    @When ("пользователь в поле '$elementName' вводит значение '$value'")
    @Given("пользователь в поле '$elementName' вводит значение '$value'")
    public void whenUserSetInputValue(String elementName, String value){
        posSteps.setValue(elementName, value);
    }


    @Then("пользователь проверяет, что в поле '$elementName' значение '$value'")
    public void whenUserCheckTextViewValue(String elementName, String value){
        posSteps.assertText(elementName, value);
    }

    @Then("пользователь проверяет, что кнопка '$buttonName' недоступна для нажатия")
    public void whenUserCheckButtonIsEnable(String elementNamevalue){
        posSteps.asserButtonEnabled(elementNamevalue, false);
    }




    @When("пользователь нажимает на кнопку '$buttonName'")
    @Given("пользователь нажимает на кнопку '$buttonName'")
    public void whenTheUserClicksOnButton(String buttonName) {
        posSteps.clickOnButton(buttonName);
    }

    @Given("пользователь находится на экране '$currentPage'")
    @When("пользователь находится на экране '$currentPage'")
    @Alias("пользователь переходит на экран '$currentPage'")
    public void whenTheUserSwitchPage(String currentPage) {
        posSteps.setCurrentPageObject(currentPage);
    }


    @When("пользователь открывает боковое меню и нажимает на элемент '$item'")
    public void whenTheUserOpensDrawerAndClickOnItem(String item) {
        posSteps.openDrawerAndClickOnDrawerOption(item);
    }

    @Given("пользователь набирает в поле поиска товаров '$productSearchQuery'")
    @When("пользователь набирает в поле поиска товаров '$productSearchQuery'")
    @Alias("пользователь набирает в поле поиска товаров productSearchQuery")
    public void whenTheUserTypeProductSearchQuery(String productSearchQuery) {
        posSteps.inputProductSearchQuery(productSearchQuery);
    }

    @Then("пользователь проверяет, что заголовок '$expectedTitle'")
    public void thenTheUserChecksTheTitle(String expectedTitle) {
        posSteps.assertActionBarTitle(expectedTitle);
    }

    @Then("пользователь видит результат поиска, в котором присутствует товары в количестве '$count'")
    public void thenUserChecksProductSearchResult(Integer count) {
        posSteps.assertSearchProductsResult(count);
    }

    @Then("пользователь видит результат поиска, в котором присутствует товар с названием '$title'")
    public void thenUserChecksProductSearchResult(String productTitle) {
        posSteps.assertSearchProductsResult(productTitle);
    }

    @Then("пользователь проверяет, что выбранный магазин это '$store'")
    public void thenTheUserChecksTheStore(String store) {
        posSteps.assertStore(store);
    }

    @Then("пользователь проверяет, что у автокоплитного поля есть сообщение '$expected'")
    public void thenTheUserChecksTheAutocompleteSearchEmptyTitleLabel(String expected) {
        posSteps.assertSearchResultEmptyLabelText(expected);
    }

    @Then("пользователь проверяет, что в чеке продажи есть сообщение '$expected'")
    public void thenTheUserChecksTheReceiptEmptyTitleLabel(String expected) {
        posSteps.assertReceiptEmptyLabelText(expected);
    }

    @Given("пользователь тапает по товару с названием '$title'")
    @When("пользователь тапает по товару с названием '$title'")
    public void thenTheUserTapOnProductInSearchResultWithTitle(String title) {
        posSteps.tapOnProductInSearchResultWithTitle(title);
    }

    @Given("пользователь тапает по товару с названием '$title' в чеке продажи")
    @When("пользователь тапает по товару с названием '$title' в чеке продажи")
    public void thenTheUserTapOnProductInReceiptWithTitle(String title) {
        posSteps.tapOnProductInReceiptWithTitle(title);
    }

    @Then("пользователь видит чек продажи '$count' в котором присутствует товары в количестве")
    public void thenUserChecksReceiptItemsCount(Integer count) {
        posSteps.assertReceiptItemsCount(count);
    }

    @Then("пользователь видит чек продажи в котором присутствует товары $examplesTable")
    public void thenUserChecksReceipt(ExamplesTable examplesTable) {
        posSteps.assertReceiptContainsProducts(examplesTable);
    }

    @Then("пользователь видит кнопку регистрации продажи с текстом '$expected'")
    public void thenTheUserChecksTheReceiptTotal(String expected) {
        posSteps.assertReceiptTotalButtonText(expected);
    }

    @Then("пользователь видит что кнопка '$buttonName' содержит текст '$expectedLabel'")
    public void thenTheUserChecksTheCleraReceiptButtonLabel(String buttonName, String expectedLabel) {
        posSteps.assertText(buttonName, expectedLabel);
    }

    @Then("пользователь видит что кнопка удаления товарной позиции вернулась в исходное состояние")
    public void thenTheUserChecksTheCleraReceiptButtonLabel() {
        posSteps.assertText("Удалить из чека", "УДАЛИТЬ ИЗ ЧЕКА");
    }

    @Then("пользователь видит модальное окно с заголовком '$expectedTitle' для товара '$expectedProductName' с ценой продажи '$expectedSellingPrice' и в количестве '$expectedQuantity'")
    public void thenTheUserChecksEditReceiptModal(String expectedTitle, String expectedProductName, String expectedSellingPrice, String expectedQuantity) {
        posSteps.assertEditReceiptTitle(expectedTitle);
        posSteps.assertEditReceiptProductName(expectedProductName);
        posSteps.assertEditReceiptSellingPrice(expectedSellingPrice);
        posSteps.assertEditReceiptQuantity(expectedQuantity);
    }


}
