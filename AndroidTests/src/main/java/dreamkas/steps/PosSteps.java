package dreamkas.steps;

import dreamkas.pageObjects.PosPage;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class PosSteps extends ScenarioSteps {

    PosPage posPage;

    @Step
    public void assertActionBarTitle(String expectedTitle) {
        assertThat(posPage.getActionBarTitle(), is(expectedTitle));
    }

    @Step
    public void chooseSpinnerItemWithValue(String value) {
        posPage.chooseSpinnerItemWithValue(value);
    }

    @Step
    public void clickOnSaveStoreSettings() {
        posPage.clickOnSaveStoreSettings();
    }

    @Step
    public void assertStore(String store) {
        assertThat(posPage.getStore(), is(store));
    }

    @Step
    public void openDrawerAndClickOnDrawerOption(String menuOption) {
        posPage.openDrawerAndClickOnDrawerOption(menuOption);
    }
}
