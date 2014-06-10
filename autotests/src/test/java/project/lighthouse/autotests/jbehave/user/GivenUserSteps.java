package project.lighthouse.autotests.jbehave.user;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.administrator.UserSteps;

public class GivenUserSteps {

    @Steps
    UserSteps userSteps;

    @Given("the user opens create new user page")
    public void givenTheUserOpensCreateNewUserPage() {
        userSteps.userCreatePageOpen();
    }

    @Given("the user is on the users list page")
    public void givenTheUserIsOnTheUsersListPage() {
        userSteps.userListPageOpen();
    }

    @Given("the user opens user edit page")
    public void givenTheUserOpensUserEditPage() {
        userSteps.userEditPageOpen();
    }
}
