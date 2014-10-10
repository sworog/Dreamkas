package dreamkas.jbehave;

import dreamkas.steps.PosSteps;
import net.thucydides.core.annotations.Steps;

import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @When("пользователь выбирает магазин с именем '$storeName' из списка")
    @Given("пользователь выбирает магазин с именем '$storeName' из списка")
    public void whenTheUserChooseStoreWithName(String storeName) {
        posSteps.chooseSpinnerItemWithValue(storeName);
    }

    @When("пользователь нажимает на кнопку 'Перейти к кассе'")
    @Given("пользователь нажимает на кнопку 'Перейти к кассе'")
    public void whenTheUserClicksOnPosNavigateButton() {
        posSteps.clickOnSaveStoreSettings();
    }

    @When("пользователь открывает боковое меню и нажимает на элемент '$item'")
    public void whenTheUserOpensDrawerAndClickOnItem(String item) {
        posSteps.openDrawerAndClickOnDrawerOption(item);
    }

    @When("пользователь набирает в поле поиска товаров '$productSearchQuery'")
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
}
