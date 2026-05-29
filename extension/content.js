console.log("LA Jobs AI loaded");

function extractJobData() {

    const title =
        document.querySelector(
            '.job-details-jobs-unified-top-card__job-title h1'
        )?.innerText ||

        document.querySelector('h1')
            ?.innerText ||

        '';

    const company =
        document.querySelector(
            '.job-details-jobs-unified-top-card__company-name'
        )?.innerText ||

        document.querySelector(
            '.job-details-jobs-unified-top-card__primary-description-container a'
        )?.innerText ||

        '';

    const description =
        document.querySelector(
            '.jobs-description-content__text'
        )?.innerText ||

        document.querySelector(
            '.jobs-description'
        )?.innerText ||

        '';

    const jobData = {
        title,
        company,
        description,
        url: window.location.href
    };

    console.log(
        "SENDING JOB:",
        jobData
    );

    sendToApi(jobData);
}

async function sendToApi(jobData) {

    try {

        const response = await fetch(
            'http://127.0.0.1:8000/api/tracked-jobs',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(jobData)
            }
        );

        const result =
            await response.json();

        console.log(
            "JOB SAVED:",
            result
        );

    } catch (error) {

        console.error(
            "API ERROR:",
            error
        );
    }
}

document.addEventListener(
    'click',
    (event) => {

        const button =
            event.target.closest(
                '#jobs-apply-button-id'
            );

        if (!button) {
            return;
        }

        console.log(
            'APPLY BUTTON CLICKED'
        );

        setTimeout(() => {

            extractJobData();

        }, 1000);
    }
);