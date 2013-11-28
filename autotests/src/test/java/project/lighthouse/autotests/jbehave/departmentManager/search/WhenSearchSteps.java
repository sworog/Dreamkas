package project.lighthouse.autotests.jbehave.departmentManager.search;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

public class WhenSearchSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @When("the user searches writeOff by number '$number'")
    public void whenTheUserSearchesWriteOffByNumber(String number) {
        writeOffSteps.writeOffSearch(number);
    }

    @When("the user clicks the writeOff search buttton and starts the search")
    public void whenTheUserClicksTheWriteOffSearchButton() {
        writeOffSteps.searchButtonClick();
    }

    @When("the user clicks on the search result writeOff with number '$number'")
    public void whenTheUserClicksTheWriteOffSearchResult(String number) {
        writeOffSteps.writeOffSearchResultClick(number);
    }
}
