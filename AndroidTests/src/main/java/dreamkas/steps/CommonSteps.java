package dreamkas.steps;

import dreamkas.screenObjects.ScreenObject;
import net.thucydides.core.annotations.Step;
import org.hamcrest.Matchers;
import org.junit.Assert;

public class CommonSteps {

    ScreenObject screenObject;

    @Step
    public void assertCurrentActivity(String expectedActivity) {
        Assert.assertThat(
                screenObject.getAppiumDriver().currentActivity(),
                Matchers.is(expectedActivity));
    }
}
