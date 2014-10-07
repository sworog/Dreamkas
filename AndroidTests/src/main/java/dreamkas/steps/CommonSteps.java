package dreamkas.steps;

import dreamkas.pageObjects.CommonPageObject;
import dreamkas.pageObjects.elements.ConfirmationElement;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class CommonSteps extends ScenarioSteps{

    CommonPageObject commonPageObject;
    ConfirmationElement confirmationElement;

    //Workaround for activity wait
    @Step
    public void assertCurrentActivity(String expectedActivity) {
        String currentActivity = commonPageObject.getCurrentActivity();
        int numberCount = 1;
        while (!currentActivity.equals(expectedActivity) && numberCount <= 10) {
            try {
                Thread.sleep(1000);
            } catch (InterruptedException e) {
                throw new AssertionError(e);
            }
            currentActivity = commonPageObject.getCurrentActivity();
            numberCount++;
        }
        if(numberCount <=10 && !currentActivity.equals(expectedActivity)) {
            assertThat(
                    commonPageObject.getCurrentActivity(),
                    is(expectedActivity));
        }
    }

    @Step
    public void resetApp() {
        commonPageObject.getAppiumDriver().resetApp();
    }

    @Step
    public void closeApp() {
        commonPageObject.closeApp();
    }

    @Step
    public void launchApp() {
        commonPageObject.launchApp();
    }

    @Step
    public void clickOnConfirmButton() {
        confirmationElement.clickOnConfirmButton();
    }
}
