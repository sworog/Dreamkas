package ru.dreamkas.jbehave.catalog;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import ru.dreamkas.steps.catalog.CatalogSteps;

public class WhenCatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @When("the user clicks on the add new group button on the catalog page")
    @Alias("пользователь нажимает на кнопку создать группу")
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

    @When("the user clicks on close icon in create new group modal window")
    public void whenTheUserConfirmsCancelInCreateNewGroupModalWindow() {
        catalogSteps.createGroupModalPageCloseIconClick();
    }

    @When("the user inputs '$name' in group name field in edit group modal window")
    public void whenTheUserInputsNameInGroupNameFieldInEditNewGroupModalWindow(String name) {
        catalogSteps.editGroupModalPageNameInput(name);
    }

    @When("the user confirms OK in edit group modal window")
    public void whenTheUserConfirmsOKInEditNewGroupModalWindow() {
        catalogSteps.editGroupModalPageConfirmOk();
    }

    @When("the user clicks on close icon in edit group modal window")
    public void whenTheUserConfirmsCancelInEditNewGroupModalWindow() {
        catalogSteps.editGroupModalPageCloseIconClick();
    }

    @When("the user clicks on delete group button in edit group modal window")
    public void whenTheUserClicksOnDeleteGroupButtonInEditGroupModalWindow() {
        catalogSteps.editGroupModalPageDeleteGroupButtonClick();
    }

    @When("the user clicks on the edit group icon")
    @Alias("пользователь нажимает на кнопку редактирования группы")
    public void whenTheUserClicksOnTheEditGroupIcon() {
        catalogSteps.editGroupIconClick();
    }

    @When("the user clicks on the back link long arrow icon on the group page")
    public void whenTheUserClicksOnTheBackLinkLongArrowIconOnTheGroupPage() {
        catalogSteps.backArrowButtonClick();
    }

    @When("the user clicks on delete group confirm button in edit group modal window")
    public void whenTheUserClicksOnDeleteGroupConfirmButtonInEditGroupModalWindow() {
        catalogSteps.editGroupModalPageDeleteGroupConfirmButtonClick();
    }

}
