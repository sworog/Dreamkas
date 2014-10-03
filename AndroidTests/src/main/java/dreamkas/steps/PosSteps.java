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
}
