package project.lighthouse.autotests.pages.user;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;

public class UserCardPage extends CommonPageObject {

    @FindBy(className = "page__backLink")
    @SuppressWarnings("unused")
    private WebElement pageBackLinkWebElement;

    public UserCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name");
        put("email");
    }

    public void pageBackLinkClick() {
        findVisibleElement(pageBackLinkWebElement).click();
    }
}
