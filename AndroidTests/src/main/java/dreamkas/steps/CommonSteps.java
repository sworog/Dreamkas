package dreamkas.steps;

import dreamkas.screenObjects.ScreenObject;
import net.thucydides.core.annotations.Step;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.*;

public class CommonSteps {

    ScreenObject screenObject;

    @Step
    public void assertCurrentActivity(String expectedActivity) {
        assertThat(
                screenObject.getCurrentActivity(),
                is(expectedActivity));
    }
}
