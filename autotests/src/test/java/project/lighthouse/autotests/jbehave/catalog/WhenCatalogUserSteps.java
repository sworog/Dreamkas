package project.lighthouse.autotests.jbehave.catalog;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.catalog.CatalogSteps;

public class WhenCatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @When("the user clicks on the add new group button on the catalog page")
    public void whenTheUserClicksOnTheAddNewGroupButtonOnTheCatalogPage() {
        catalogSteps.addGroupButtonClick();
    }

    @When("the user clicks on the group with name '$groupName'")
    public void whenTheUserClicksOnTheGroupWithName(String groupName) {
        catalogSteps.groupWithNameClick(groupName);
    }

    @When("the user inputs '$name' in group name field in create new group modal window")
    public void whenTheUserInputsNameInGroupNameFieldInCreateNewGroupModalWindow(String name) {
        catalogSteps.createGroupModalPageNameInput(name);
    }

    @When("the user generates symbols with count '$count' in the create group modal window name field")
    public void whenTheUserGeneratesSymbolsWithCountInNameField(int count) {
        catalogSteps.createGroupModalPageNameInputGenerate(count);
    }

    @When("the user generates symbols with count '$count' in the edit group modal window name field")
    public void whenTheUserGeneratesSymbolsWithCountInTheEditGroupNameField(int count) {
        catalogSteps.editGroupModalPageNameInputGenerate(count);
    }

    @When("the user confirms OK in create new group modal window")
    public void whenTheUserConfirmsOKInCreateNewGroupModalWindow() {
        catalogSteps.createGroupModalPageConfirmOk();
    }

    @When("the user confirms Cancel in create new group modal window")
    public void whenTheUserConfirmsCancelInCreateNewGroupModalWindow() {
        catalogSteps.createGroupModalPageConfirmCancel();
    }

    @When("the user inputs '$name' in group name field in edit group modal window")
    public void whenTheUserInputsNameInGroupNameFieldInEditNewGroupModalWindow(String name) {
        catalogSteps.editGroupModalPageNameInput(name);
    }

    @When("the user confirms OK in edit group modal window")
    public void whenTheUserConfirmsOKInEditNewGroupModalWindow() {
        catalogSteps.editGroupModalPageConfirmOk();
    }

    @When("the user confirms Cancel in edit group modal window")
    public void whenTheUserConfirmsCancelInEditNewGroupModalWindow() {
        catalogSteps.editGroupModalPageConfirmCancel();
    }

    @When("the user clicks on delete group button in edit group modal window")
    public void whenTheUserClicksOnDeleteGroupButtonInEditGroupModalWindow() {
        catalogSteps.editGroupModalPageDeleteGroupButtonClick();
    }

    @When("the user confirms OK in delete group modal window")
    public void whenTheUserConfirmsOKInDeleteNewGroupModalWindow() {
        catalogSteps.deleteGroupModalPageConfirmOk();
    }

    @When("the user confirms Cancel in delete group modal window")
    public void whenTheUserConfirmsCancelInDeleteNewGroupModalWindow() {
        catalogSteps.deleteGroupModalPageConfirmCancel();
    }

    @When("the user clicks on the edit group icon")
    public void whenTheUserClicksOnTheEditGroupIcon() {
        catalogSteps.editGroupIconClick();
    }
}
