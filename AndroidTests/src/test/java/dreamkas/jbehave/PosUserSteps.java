package dreamkas.jbehave;

import dreamkas.steps.PosSteps;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @When("пользователь выбирает магазин с именем '$storeName' из списка")
    public void whenTheUserChooseStoreWithName(String storeName) {
        posSteps.chooseSpinnerItemWithValue(storeName);
    }

    @When("пользователь нажимает на кнопку 'Перейти к кассе'")
    public void whenTheUserClicksOnPosNavigateButton() {
        posSteps.clickOnSaveStoreSettings();
    }

    @When("пользователь открывает боковое меню и нажимает на элемент '$item'")
    public void whenTheUserOpensDrawerAndClickOnItem(String item) {
        posSteps.openDrawerAndClickOnDrawerOption(item);
    }

    @Then("пользователь проверяет, что заголовок '$expectedTitle'")
    public void thenTheUserChecksTheTitle(String expectedTitle) {
        posSteps.assertActionBarTitle(expectedTitle);
    }

    @Then("пользователь проверяет, что выбранный магазин это '$store'")
    public void thenTheUserChecksTheStore(String store) {
        posSteps.assertStore(store);
    }
}
