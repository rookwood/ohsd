# OSHA Hearing Screening Database


#### Storage of screening results
- [x] Serial audiograms
  - [x] Threshold data
  - [x] Pretest noise exposure
  - [x] Hearing protection used since last evaluation
  - [x] Otoscopy results (pass/fail)
  - [x] Evaluation comments

#### Demographics
- [x] Patient information
  - [x] Intake medical questionaire
  TODO: Intake validation, resource
    - [x] On registration, use previous intake as baseline for changes
  - [x] Basic demographics

#### Staff information
- [x] Evaluator information
  - [x] Qualifications
  - [x] License number
  - [x] Degree

#### User management
- [x] Evaluator registration
 - [x] Creatable by admin only
 - [x] Send email to new user to set password for account

#### Evaluation of results
- [x] Standard Threshold shift
  - [x] Without age adjustment
  - [x] With age adjustment
  - [x] Calculate automatically when new test added

#### Complete new patient flow
- [ ] Add new patient
- [ ] Arrive for encounter
- [ ] Register intake information
- [ ] Log results
  - [ ] Complete by either adding thresholds or as CNT (e.g. cerumen)
  - [ ] Flag for rescreen if necessary

#### Complete returning patient flow
- [ ] Arrive for encounter
- [ ] Register intake information
- [ ] Log results
  - [ ] Complete by either adding thresholds or as CNT (e.g. cerumen)
  - [ ] Flag for rescreen if necessary


#### Generate patient and employer letters
- [ ] Results from STS
- [ ] Recommendations
  - [ ] Letter to patient and employer

#### Misc todos
- [ ] Add all domain events
- [ ] Add policies for all requests
- [ ] Consider multi-tenancy
