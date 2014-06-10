package project.lighthouse.autotests.pages.user.localNavigation;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class UserLocalNavigation extends CommonPageObject {

    public UserLocalNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void editButtonClick() {
        new NavigationLinkFacade(this, "Редактировать");
    }

    public void logOutButtonClick() {
        new NavigationLinkFacade(this, "Выйти").click();
    }
}
