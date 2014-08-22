package project.lighthouse.autotests.common;

import org.openqa.selenium.By;

public interface ModalWindowPageObject extends GeneralPageObject {

    public String getTitle();

    public void confirmationOkClick();

    public void deleteButtonClick();

    public void confirmDeleteButtonClick();
}
